<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\V1\Web;

use App\Lib\MidTrans\CreditCardToken;
use App\Lib\MidTrans\Payment\BcaKlikPay;
use App\Lib\MidTrans\Payment\BcaVirtualAccount;
use App\Lib\MidTrans\Payment\BniVirtualAccount;
use App\Lib\MidTrans\Payment\CreditCard;
use App\Lib\MidTrans\Payment\Gopay;
use App\Lib\MidTrans\Payment\MandiriBillPayment;
use App\Lib\MidTrans\Payment\MandiriClickPay;
use App\Lib\MidTrans\Payment\PermataVirtualAccount;
use App\Lib\MidTrans\Request;
use App\Lib\MidTrans\Transaction;

use Cake\Utility\Hash;

use Cake\I18n\Time;
use App\Lib\MidTrans\Token;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Validation\Validator;


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @property \App\Model\Table\CustomerCartsTable $CustomerCarts
 * @property \App\Model\Table\CustomerVouchersTable $CustomerVouchers
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\CourriersTable $Courriers
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Controller\Component\RajaOngkirComponent $RajaOngkir
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CheckoutController extends AppController
{

    /**
     * available status = 1
     * @var array
     */
    protected $customerDetailStatuses = [1];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCards');
        $this->loadModel('CustomerCarts');
        $this->loadModel('CustomerVouchers');
        $this->loadModel('Orders');
        $this->loadModel('Courriers');
        $this->loadModel('Products');

        $this->loadComponent('RajaOngkir');

    }

    protected function groupCartByBranch($cart, $product_to_couriers, \App\Model\Entity\CustomerAddrese $address)
    {
        $cart_group_origin = [];
        if ($cart['customer_cart_details']) {
            foreach($cart['customer_cart_details'] as $key => $val) {
                if (!array_key_exists($val['origin_id'], $cart_group_origin)) {
                    $courier_group = $product_to_couriers[$val['origin_id']][0];
                    if (count($product_to_couriers[$val['origin_id']]) > 1) {
                        $courier_group = call_user_func_array('array_intersect', $product_to_couriers[$val['origin_id']]);
                    }

                    $cart_group_origin[$val['origin_id']]['origin'] = $val['origin'];
                    $cart_group_origin[$val['origin_id']]['origin_id'] = $val['origin_id'];
                    $cart_group_origin[$val['origin_id']]['total_weight'] = $val['weight'] * $val['qty'];
                    $cart_group_origin[$val['origin_id']]['shipping_options'] = $this->getShipping(
                        implode(':', $courier_group),
                        $val['origin_district_id'],
                        $address->subdistrict_id,
                        $val['weight'] * $val['qty']
                    );
                    $cart_group_origin[$val['origin_id']]['data'][] = $val;

                } else {
                    $cart_group_origin[$val['origin_id']]['total_weight'] += $val['weight'] * $val['qty'];
                    $cart_group_origin[$val['origin_id']]['data'][] = $val;

                }

            }
        }
        return $cart_group_origin;
    }

    protected function getCart(callable $call = null, callable $entity = null)
    {
        return $this->CustomerCarts->find()
            ->contain(
                'CustomerCartDetails', function (\Cake\ORM\Query $q) {
                return $q
                    ->where(['CustomerCartDetails.status IN ' => $this->customerDetailStatuses]);
            })
            ->contain([
                'CustomerCartDetails' => [
                    'Products' => [
                        'fields' => [
                            'id',
                            'name',
                            'slug',
                            'price',
                        ],
                        'ProductImages' => [
                            'fields' => [
                                'name',
                                'product_id',
                            ]
                        ],
                        'ProductToCourriers' => [
                            'Courriers'
                        ],
                    ],
                    'ProductOptionPrices' => [
                        'ProductOptionValueLists' => [
                            'Options',
                            'OptionValues'
                        ],
                    ],
                    'ProductOptionStocks' => [
                        'Branches'
                    ],
                ]
            ])
            ->where(['CustomerCarts.customer_id' => $this->Auth->user('id'),'CustomerCarts.status' => 1 ])
            ->map(function (\App\Model\Entity\CustomerCart $row) use($call, $entity) {
                if (is_callable($entity)) {
                    $newEntity = clone $row;
                    call_user_func($entity, $newEntity);
                }
                $status = [
                    1 => 'available',
                    2 => 'expired',
                    3 => 'outoff stock',
                    4 => 'deleted',
                    5 => 'move to whislist'
                ];
                foreach ($row['customer_cart_details'] as $key => $vals){
                    $row->customer_cart_details[$key]->cartid = $row->customer_cart_details[$key]->id;
                    $row->customer_cart_details[$key]->status = $status[$row->customer_cart_details[$key]->status];
                    $row->customer_cart_details[$key]->name = $row->customer_cart_details[$key]->product->name;
                    $row->customer_cart_details[$key]->slug = $row->customer_cart_details[$key]->product->slug;
                    $row->customer_cart_details[$key]->regular_price = $row->customer_cart_details[$key]->product->price;


                    $row->customer_cart_details[$key]->sku = $row->customer_cart_details[$key]->product_option_price->sku;
                    $row->customer_cart_details[$key]->origin = $row->customer_cart_details[$key]->product_option_stock->branch->name;
                    $row->customer_cart_details[$key]->origin_id = $row->customer_cart_details[$key]->product_option_stock->branch->id;
                    $row->customer_cart_details[$key]->origin_district_id = $row->customer_cart_details[$key]->product_option_stock->branch->subdistrict_id;

                    $row->customer_cart_details[$key]->weight = $row->customer_cart_details[$key]->product_option_stock->weight;

                    $variant = [];
                    foreach($vals['product_option_price']['product_option_value_lists'] as $val){
                        $variant[$key][] = $val['option']['name'] .' : '. $val['option_value']['name'];
                    }

                    $row->customer_cart_details[$key]->variant = implode(', ', $variant[$key]);
                    $row->customer_cart_details[$key]->price_id = $row->customer_cart_details[$key]->product_option_price_id;
                    $row->customer_cart_details[$key]->stock_id = $row->customer_cart_details[$key]->product_option_stock_id;
                    $row->customer_cart_details[$key]->images = Hash::extract($row->customer_cart_details[$key]->product->product_images, '{n}.name');

                    $couriers = [];
                    foreach($row->customer_cart_details[$key]->product->product_to_courriers as $k => $courier) {
                        array_push($couriers, $courier['courrier']['code']);
                    }
                    //$product_to_couriers[$row->customer_cart_details[$key]->origin_id][] = $couriers;
                    $row->customer_cart_details[$key]->couriers = $couriers;

                    unset($row->customer_cart_details[$key]->created);
                    unset($row->customer_cart_details[$key]->modified);
                    unset($row->customer_cart_details[$key]->product);
                    unset($row->customer_cart_details[$key]->product_option_stock);
                    unset($row->customer_cart_details[$key]->product_option_price);
                    unset($row->customer_cart_details[$key]->id);
                    unset($row->customer_cart_details[$key]->customer_cart_id);
                    unset($row->customer_cart_details[$key]->product_option_price_id);
                    unset($row->customer_cart_details[$key]->product_option_stock_id);

                    if (is_callable($call)) {
                        call_user_func($call, $key, $row);
                    }
                }
                unset($row->id);
                unset($row->customer_id);
                unset($row->status);
                unset($row->created);
                unset($row->modified);
                return $row;
            })
            ->first();
    }

    /**
     * @return \App\Model\Entity\CustomerAddrese
     */
    protected function getAddress($address_id = null)
    {
        $customer_id = $this->Auth->user('id');
        $address =  $this->Customers->CustomerAddreses->find()
            ->where([
                'customer_id' => $customer_id
            ]);

        if ($address_id) {
            $address->where([
                'id' => $address_id
            ]);
        }

        $address = $address->orderDesc('is_primary')
        ->map(function(\App\Model\Entity\CustomerAddrese $row) {
            unset($row->customer_id);
            return $row;
        })
        ->first();

        return $address;
    }


    public function index()
    {
        //list checkout and default customer address
        $data = [];
        $customer_id = $this->Auth->user('id');

        $data['customer_address'] = $this->getAddress();

        $get_point = $this->Customers->CustomerBalances->find()
            ->where([
                'customer_id' => $customer_id
            ])
            ->first();

        $data['point'] = (int) $get_point->get('point');


        $product_to_couriers = [];
        $total = 0;
        $cart = $this->getCart(function($key, \App\Model\Entity\CustomerCart $row) use(&$product_to_couriers) {
            $product_to_couriers[$row->customer_cart_details[$key]->origin_id][] = $row->customer_cart_details[$key]->couriers;
        }, function(\App\Model\Entity\CustomerCart $row) use (&$total) {
            foreach($row['customer_cart_details'] as $val) {
                $total += (float) $val['price'] * intval($val['qty']);
            }
        });

        $data['gross_total'] = $total;

        //check if customer using voucher
        /**
         * @var \App\Model\Entity\CustomerVoucher $voucherEntity
         */
        $voucherEntity = $this->CustomerVouchers->find()
            ->where([
                'CustomerVouchers.customer_id' => $customer_id,
                'CustomerVouchers.status' => 1
            ])
            ->contain([
                'Vouchers'
            ])
            ->orderDesc('CustomerVouchers.id')
            ->first();

        if ($voucherEntity) {
            switch($voucherEntity->voucher->type) {
                case '1':
                    $discount = $voucherEntity->voucher->value / 100 * $total;
                    $data['discount'] = $discount;
                    $total = $total - $discount;
                    break;
                case '2':
                    $discount = $voucherEntity->voucher->value;
                    $data['discount'] = $discount;
                    $total = $total - $discount;
                    break;
            }

            $data['code_voucher'] = $voucherEntity->voucher->code_voucher;


        }

        $data['total'] = $total;


        //grouping by origin_id
        $cart_group_origin = $this->groupCartByBranch($cart, $product_to_couriers, $data['customer_address']);


        $data['carts'] = $cart_group_origin;

        $this->set(compact('data'));

    }


    /**
     * this method using credit card to generate token base on amount
     */
    public function makePayment()
    {
        $this->request->allowMethod('post');

        $validator = new Validator();

        $validator->requirePresence('payload')
            ->notBlank('payload')
            ->requirePresence('token')
            ->notBlank('token');


        $error = $validator->errors($this->request->getData());
        if (!$error) {
            unset($error);
            $payload = $this->request->getData('payload');
            /**
             * @var \App\Lib\MidTrans\Request $request
             */
            $request = unserialize(
                Security::decrypt(
                    base64_decode($payload),
                    Configure::read('Encrypt.salt') . $this->request->getData('token')
                )
            );

            if ($request instanceof \App\Lib\MidTrans\Request) {
                //get customer
                $customerEntity = null;
                try {
                    $customerEntity = $this->Customers->get($this->Auth->user('id'));
                } catch(\Exception $e) {

                }

                $payment = new CreditCard();
                $payment->setToken($this->request->getData('token'))
                    ->saveToken(true)
                    //->setAuthentication(true)
                    ->setCustomer(
                        $customerEntity->get('email'),
                        $customerEntity->get('first_name'),
                        $customerEntity->get('last_name'),
                        $customerEntity->get('phone')
                    )
                    ->setBillingAddress()
                    ->setShippingFromBilling();

                $request->setPaymentRequest($payment);

                $charge = $this->MidTrans->charge($request);
                //debug($request);
                //debug($charge);
                if ($charge && isset($charge['status_code'])) {
                    switch ($charge['status_code']) {
                        case 200:

                            break;
                        case 201:

                            break;
                        default:
                            $this->setResponse($this->response->withStatus(406, $charge['status_message']));
                            break;
                    }
                }
            } else {
                $this->setResponse($this->response->withStatus(406, 'Invalid payload'));
            }



        } else {
            $this->setResponse($this->response->withStatus(406, 'gagal proses payment'));
        }

        $this->set(compact('error'));
    }




    /**
     * process checkout json input params
     *
     * [
     * 'shipping' => [
     *    (int) 1 => [
     *   'code' => 'jne',
     *    'service' => 'REG'
     *    ]
     *    ],
     *    'payment_method' => 'bca_va',
     *    'address_id' => '3',
     *    'use_point' => '1'
     * ]
     */
    public function process()
    {
        $this->request->allowMethod('post');
        //debug($this->request->getData());

        $customer_id = $this->Auth->user('id');

        $validator = new Validator();

        /*$shippingValidation = new Validator();
        $shippingValidation->requirePresence('code')
            ->inList('code', ['jne', 'jnt', 'tiki', 'pos'])
            ->notBlank('code')
            ->requirePresence('service')
            ->notBlank('service');

        $validator->addNestedMany('shipping', $shippingValidation);*/

        $validator->add('use_point', 'valid_point', [
            'rule' => function($value) use($customer_id) {
                $currentPoint = $this->Customers->CustomerBalances->find()
                    ->where([
                        'customer_id' => $customer_id,
                    ])
                    ->first();
                if ($currentPoint) {
                    return $value <= $currentPoint->get('point') && $value > 0;
                }
            },
            'message' => 'Point yang di input tidak valid.'
        ]);

        $shippingValidation = new Validator();
        $shippingValidation->requirePresence('code')
            ->inList('code', ['jne', 'jnt', 'tiki', 'pos'])
            ->notBlank('code')
            ->requirePresence('service')
            ->notBlank('service');

        $branchShipping = new Validator();

        //set validator from branch database
        $find_branch = $this->CustomerCarts->find()
            ->contain(
                'CustomerCartDetails', function (\Cake\ORM\Query $q) {
                return $q
                    ->where(['CustomerCartDetails.status IN ' => $this->customerDetailStatuses]);
            })
            ->contain([
                'CustomerCartDetails' => [
                    'fields' => [
                        'id',
                        'customer_cart_id'
                    ],
                    'ProductOptionStocks' => [
                        'fields' => [
                            'branch_id'
                        ]
                    ],
                ]
            ])
            ->where(['CustomerCarts.customer_id' => $this->Auth->user('id'),'CustomerCarts.status' => 1 ])
            ->first();

        if ($find_branch) {
            $list_branches = [];
            foreach($find_branch['customer_cart_details'] as $key => $val) {
                if(!in_array($val['product_option_stock']['branch_id'], $list_branches)) {
                    $branchShipping->requirePresence($val['product_option_stock']['branch_id']);
                    $branchShipping->addNested($val['product_option_stock']['branch_id'], $shippingValidation);
                }
            }
            unset($list_branches);
        }
        //set validator from branch database

        $validator->addNested('shipping', $branchShipping)
            ->requirePresence('shipping');



        $validator->requirePresence('payment_method')
            ->inList('payment_method', [
                'credit_card',
                'mandiri_billpayment',
                'bca_va',
                'permata_va',
                'bni_va',
                'bca_klikpay',
                'mandiri_clickpay',
                'gopay'
            ]);

        if ($payment_method = $this->request->getData('payment_method') == 'credit_card') {
            $validator
                ->notBlank('card_id')
                ->add('card_id', 'check_card', [
                    'rule' => function($value) use($customer_id) {
                        return $this->CustomerCards->find()
                                ->where([
                                    'customer_id' => $customer_id,
                                    'id' => $value
                                ])
                                ->count() > 0;
                    },
                    'message' => 'Silahkan masukan credit credit card'
                ]);

            if (!$this->request->getData('card_id')) {

                $validator->requirePresence('card_number')
                    ->notBlank('card_number')
                    ->creditCard('card_number')
                    ->requirePresence('card_exp_month')
                    ->notBlank('card_exp_month')
                    ->minLength('card_exp_month', 2)
                    ->maxLength('card_exp_month', 2)
                    ->numeric('card_exp_month')
                    ->requirePresence('card_exp_year')
                    ->notBlank('card_exp_year')
                    ->minLength('card_exp_year', 4)
                    ->maxLength('card_exp_year', 4)
                    ->numeric('card_exp_year');
            }

            $validator
                ->requirePresence('cvv')
                ->notBlank('cvv')
                ->numeric('cvv')
                ->maxLength('cvv', 3)
                ->minLength('cvv', 3);
        }

        $validator->requirePresence('address_id')
            ->notBlank('address_id', 'Silahkan pilih alamat yang dikirim')
            ->add('address_id', 'exists_address', [
                'rule' => function($value) use($customer_id) {
                    return $this->Customers->CustomerAddreses->find()
                        ->where([
                            'customer_id' => $customer_id,
                            'id' => $value
                        ])
                        ->count() > 0;
                },
                'message' => 'Silahkan pilih alamat yang dikirim'
            ]);

        $error = $validator->errors($this->request->getData());
        if ($error) {
            $this->setResponse($this->response->withStatus(406, 'gagal proses checkout'));
        } else {
            unset($error);
            //process checkout
            $shipping = $this->request->getData('shipping');
            $address_id = $this->request->getData('address_id');


            $product_to_couriers = [];
            /**
             * @var \App\Model\Entity\CustomerCart $cartEntity
             */
            $cartEntity = null;
            $cartDetailEntities = [];
            $cart = $this->getCart(function($key, \App\Model\Entity\CustomerCart $row) use(&$product_to_couriers, &$cartDetailEntities) {
                $product_to_couriers[$row->customer_cart_details[$key]->origin_id][] = $row->customer_cart_details[$key]->couriers;
                /**
                 * @var \App\Model\Entity\CustomerCartDetail $customer_cart_details
                 */
                $customer_cart_details =  $row->customer_cart_details[$key];
                $customer_cart_details->set('id', $customer_cart_details->cartid);
                $cartDetailEntities[$key] = $customer_cart_details;
            }, function(\App\Model\Entity\CustomerCart $row) use (&$cartEntity) {
                $cartEntity = $row;
            });
            //grouping by origin_id
            $cart = $this->groupCartByBranch($cart, $product_to_couriers, $this->getAddress($address_id));


            if ($cart) {
                $this->Orders->getConnection()->begin();


                $invoice = strtoupper(date('ymdHs') . Security::randomString(4));
                $addresses = $this->getAddress($address_id);
                $gross_total = 0;
                $use_point = (int)$this->request->getData('use_point');

                $trx = new Transaction($invoice);


                $order_detail_entities = [];
                $order_detail_product_entities = [];
                foreach ($cart as $origin_id => $item) {
                    $subtotal = 0;
                    foreach ($item['data'] as $val) {
                        $trx->addItem($val['product_id'], $val['price'], $val['qty'], $val['name']);
                        $subtotal += $val['price'] * $val['qty'];
                        $gross_total += $val['price'] * $val['qty'];
                        //debug($val);
                        $order_detail_product_entities[$origin_id][] = $this
                            ->Orders
                            ->OrderDetails
                            ->OrderDetailProducts
                            ->newEntity([
                                'product_id' => $val['product_id'],
                                'qty' => $val['qty'],
                                'price' => $val['price'],
                                'total' => $val['price'] * $val['qty'],
                                'in_flashsale' => $val['in_flashsale'],
                                'in_groupsale' => $val['in_groupsale'],
                                'product_option_stock_id' => $val['stock_id'],
                                'product_option_price_id' => $val['price_id'],
                                'comment' => $val['comment']
                            ]);
                    }


                    //selected shipping

                    foreach ($shipping as $branch => $val) {
                        if ($origin_id == $branch) {
                            foreach ($item['shipping_options'] as $shipping_option) {
                                if ($shipping_option['code'] == $val['code'] && strtolower($shipping_option['service']) == strtolower($val['service'])) {
                                    $trx->addItem($origin_id, $shipping_option['cost'], 1, $shipping_option['code'] . '-' . $shipping_option['service']);
                                    $courierEntity = $this->Courriers->find()
                                        ->where([
                                            'code' => $val['code']
                                        ])
                                        ->first();

                                    $branchEntity = $this->Orders->OrderDetails->Branches->find()
                                        ->where([
                                            'id' => $origin_id
                                        ])
                                        ->first();


                                    $gross_total += $shipping_option['cost'];
                                    $order_detail_entities[$origin_id] = $this->Orders->OrderDetails->newEntity([
                                        'branch_id' => $origin_id,
                                        'courrier_id' => $courierEntity->get('id'),
                                        'province_id' => $branchEntity->get('provice_id'), //TODO fix later
                                        'city_id' => $branchEntity->get('city_id'),
                                        'subdistrict_id' => $branchEntity->get('subdistrict_id'),
                                        'shipping_code' => $shipping_option['code'],
                                        'shipping_service' => $shipping_option['service'],
                                        'shipping_weight' => $item['total_weight'],
                                        'total' => $subtotal + $shipping_option['cost'],
                                        'shipping_cost' => $shipping_option['cost'],
                                        'order_status_id' => 1,
                                        'awb' => ''
                                    ]);
                                }
                            }
                        }
                    }
                }


                $total = $gross_total - $use_point;

                if ($use_point > 0) {
                    $trx->addItem('point', -$use_point, 1, 'Using Point Customer');
                }

                //check voucher claim
                /**
                 * @var \App\Model\Entity\CustomerVoucher $customerVoucherEntity
                 */
                $customerVoucherEntity = $this->CustomerVouchers->find()
                    ->where([
                        'CustomerVouchers.customer_id' => $customer_id,
                        'CustomerVouchers.status' => 1
                    ])
                    ->contain([
                        'Vouchers'
                    ])
                    ->orderDesc('CustomerVouchers.id')
                    ->first();

                if ($customerVoucherEntity) {
                    $discount = 0;
                    switch($customerVoucherEntity->voucher->type) {
                        case '1':
                            $discount = $customerVoucherEntity->voucher->value / 100 * $total;
                            $total = $total - $discount;
                            break;
                        case '2':
                            $discount = $customerVoucherEntity->voucher->value;
                            $total = $total - $discount;
                            break;
                    }

                    $trx->addItem(
                        'vocher' . $customerVoucherEntity->get('id'),
                        -$discount,
                        1,
                        'Using voucher ' . $customerVoucherEntity->voucher->code_voucher
                    );

                }



                $orderEntity = $this->Orders->newEntity([
                    'invoice' => $invoice,
                    'customer_id' => $customer_id,
                    'province_id' => $addresses->get('province_id'),
                    'city_id' => $addresses->get('city_id'),
                    'subdistrict_id' => $addresses->get('subdistrict_id'),
                    'address' => $addresses->get('address'),
                    'use_point' => $use_point,
                    'gross_total' => $gross_total,
                    'total' => $total,
                    'voucher_id' => $customerVoucherEntity ? $customerVoucherEntity->get('voucher_id') : null
                ]);



                //get customer
                $customerEntity = null;
                try {
                    $customerEntity = $this->Customers->get($this->Auth->user('id'));
                } catch(\Exception $e) {

                }

                $payment_method = $this->request->getData('payment_method');

                $data['payment_method'] = $payment_method;
                $data['payment_amount'] = $trx->getAmount();

                $payment = null;


                switch ($payment_method) {
                    case 'credit_card':
                        //for credit card

                        //request new token for gross_amount from saved card

                        $customer_card_entity = $this->CustomerCards->find()
                            ->where([
                                'customer_id' => $customer_id,
                                'id' => $this->request->getData('card_id')
                            ])
                            ->first();

                        if ($customer_card_entity) {

                            /*
                            $payment = new CreditCard();
                            $payment->setToken($this->request->getData('token'))
                                ->saveToken(true)
                                ->setAuthentication(true)
                                ->setCustomer(
                                    $customerEntity->get('email'),
                                    $customerEntity->get('first_name'),
                                    $customerEntity->get('last_name'),
                                    $customerEntity->get('phone')
                                )
                                ->setBillingAddress()
                                ->setShippingFromBilling();
                            */

                            /**
                             * if credit card generate token base on amount first
                             *
                             */

                            $credit_card_token = new CreditCardToken();
                            $token = $credit_card_token->setToken($customer_card_entity->get('token'))
                                ->setCvv($this->request->getData('cvv'))
                                ->setSecure(true)
                                ->request($trx->getAmount());
                        } else {

                            $credit_card_token = new CreditCardToken(
                                $this->request->getData('card_number'),
                                $this->request->getData('card_exp_month'),
                                $this->request->getData('card_exp_year'),
                                $this->request->getData('cvv')
                            );

                            $token = $credit_card_token->setSecure(true)
                                ->request($trx->getAmount());
                        }

                        $data[$payment_method] = array_filter([
                            'redirect_url' => $token->redirect_url,
                            'token' => $token->token_id,
                            'bank' => $token->bank
                        ]);


                        break;

                    case 'bca_va':
                        //for bca
                        $payment = (new BcaVirtualAccount(1111111))
                            ->setSubCompanyCode(1111);
                        break;

                    case 'mandiri_billpayment':
                        $payment = (new MandiriBillPayment());
                        break;

                    case 'permata_va':
                        //for permata
                        $payment = (new PermataVirtualAccount())
                            ->setRecipientName($customerEntity->get('first_name'));
                        break;

                    case 'bni_va':
                        $payment = new BniVirtualAccount('111111');
                        break;

                    case 'bca_klikpay':
                        $payment = new BcaKlikPay();
                        break;

                    case 'mandiri_clickpay':
                        $token = (new \App\Lib\MidTrans\CreditCardToken())
                            ->setCardNumber('4111 1111 1111 1111')
                            ->request($trx->getAmount());
                        if ($token->status_code == 200) {
                            $payment = new MandiriClickPay($token->token_id, '54321', '000000');
                        }
                        break;

                    case 'gopay':
                        $payment = new Gopay('http://php.net');
                        break;

                    default:
                        $payment = null;
                        break;
                }


                $request = new Request($payment);
                $request->addTransaction($trx);



                $request->setCustomer(
                    $customerEntity->get('email'),
                    $customerEntity->get('first_name'),
                    $customerEntity->get('last_name'),
                    $customerEntity->get('phone')
                );

                $process_save_order = true;
                $process_payment_charge = true;
                try {
                    if ($this->Orders->save($orderEntity)) {
                        if ($customerVoucherEntity instanceof \App\Model\Entity\CustomerVoucher) {
                            $customerVoucherEntity->set('status', 2);
                            $this->CustomerVouchers->save($customerVoucherEntity);
                        }
                        foreach ($order_detail_entities as $origin_id => $detailEntity) {
                            $detailEntity = $this
                                ->Orders
                                ->OrderDetails
                                ->patchEntity($detailEntity, [
                                    'order_id' => $orderEntity->get('id')
                                ],
                                    ['validate' => false]
                                );
                            if ($this->Orders->OrderDetails->save($detailEntity)) {
                                foreach ($order_detail_product_entities[$origin_id] as $detailProductEntity) {
                                    $detailProductEntity = $this
                                        ->Orders
                                        ->OrderDetails
                                        ->OrderDetailProducts
                                        ->patchEntity($detailProductEntity, [
                                            'order_detail_id' => $detailEntity->get('id')
                                        ],
                                            ['validate' => false]
                                        );

                                    if ($this
                                        ->Orders
                                        ->OrderDetails
                                        ->OrderDetailProducts
                                        ->save($detailProductEntity)) {
                                        $this->Products->ProductStockMutations->saving(
                                            $detailProductEntity->get('product_option_stock_id'),
                                            3,
                                            -$detailProductEntity->get('qty'),
                                            ''
                                        );
                                    } else {
                                        $process_save_order = false;
                                    }
                                }
                            } else {
                                $process_save_order = false;
                            }

                        }


                        $cartEntity->set('status', 3);
                        if ($this->CustomerCarts->save($cartEntity)) {
                            if (is_array($cartDetailEntities)) {
                                /**
                                 * @var \App\Model\Entity\CustomerCartDetail[] $cartDetailEntities
                                 */
                                foreach($cartDetailEntities as $cartDetailEntity) {
                                    $cartDetailEntity->set('status', 4);
                                    $this->CustomerCarts->CustomerCartDetails->save($cartDetailEntity);
                                }
                            }
                        }

                        //process mutation point here
                        if ($this->request->getData('use_point') > 0) {
                            $this
                                ->Customers
                                ->CustomerMutationPoints
                                ->saving(
                                    $customer_id,
                                    1,
                                    - intval($this->request->getData('use_point')),
                                    'penggunaan point untuk belanja'
                                );
                        }

                    } else {
                        $process_save_order = false;
                    }
                } catch(\Exception $e) {
                    $process_save_order = false;
                }


                if ($process_save_order) {

                    if ($payment_method == 'credit_card') {
                        $data['payload'] = base64_encode(Security::encrypt(serialize($request), Configure::read('Encrypt.salt') . $token->token_id));
                    } else {
                        //process charge exception credit card
                        try {
                            $charge = $this->MidTrans->charge($request);
                            /*
                             * status_code 200 is success and using credit card
                             * status_code 201 is pending and using gopay, virtual_account, clickpay
                             */

                            if ($charge && isset($charge['status_code'])) {
                                switch ($charge['status_code']) {
                                    case 200:
                                        $data['payment_status'] = 'success';
                                        $data['payment'] = $charge;
                                        break;
                                    case 201:
                                        //process pending need response to frontend
                                        $data['payment_status'] = 'pending';
                                        $data['payment'] = $charge;
                                        break;
                                    default:
                                        $data['payment_status'] = 'failed';
                                        $this->setResponse($this->response->withStatus(406, 'Proses payment gagal'));
                                        $process_payment_charge = false;
                                        break;
                                }


                            }

                        } catch(\Exception $e) {
                            $this->Orders->getConnection()->rollback();
                            $process_payment_charge = false;
                        }
                    }

                }

                if ($process_save_order && $process_payment_charge) {
                    $this->Orders->getConnection()->commit();
                } else {
                    $this->setResponse($this->response->withStatus(406, 'Proses payment gagal'));
                }

            } else {
                $this->setResponse($this->response->withStatus(404, 'Keranjang belanja anda kosong'));
            }



        }



        $this->set(compact('data', 'error'));
    }

    /**
     * apply voucher
     */
    public function applyVoucher()
    {
        $this->request->allowMethod('post');
        $customer_id = $this->Auth->user('id');
        $code_voucher = $this->request->getData('code_voucher');
        if ($code_voucher) {
            $find = $this->CustomerVouchers->Vouchers->find()
                ->where([
                    'code_voucher' => $code_voucher,
                    'status' => 1
                ])
                ->where(function (\Cake\Database\Expression\QueryExpression $exp) {
                    $now = (Time::now())->format('Y-m-d H:i:s');
                    return $exp->lte('date_start', $now)
                        ->gte('date_end', $now);
                })
                ->first();

            if ($find) {

                //check quota
                $total_used_voucher = $this->CustomerVouchers->find()
                    ->where([
                        'voucher_id' => $find->get('id')
                    ])
                    ->count();

                if ($total_used_voucher <= $find->get('qty')) {
                    //save customer voucher with status pending

                    $exists = $this->CustomerVouchers->find()
                        ->where([
                            'customer_id' => $customer_id,
                            'voucher_id' => $find->get('id'),
                            'status' => 1
                        ])
                        ->first();
                    $error = false;

                    if (!$exists) {
                        $customer_voucher_entity = $this->CustomerVouchers->newEntity([
                            'customer_id' => $customer_id,
                            'voucher_id' => $find->get('id'),
                            'status' => 1
                        ]);
                        if(!$this->CustomerVouchers->save($customer_voucher_entity)) {
                            $error_message = array_values($customer_voucher_entity->getError('voucher_id'));
                            $error_message = count($error_message) > 0 ? $error_message[0] : 'Vocher tidak ditemukan / terjadi kesalahan sistem';
                            $this->setResponse($this->response->withStatus(406,  $error_message));
                            $error = true;
                        }

                    }

                    if (!$error) {
                        $data = [
                            'code_voucher' => $code_voucher,
                            'type' => $find->get('type'),
                            'description' => $find->get('type') == 1 ? 'discount by percent' : 'discount by value',
                            'value' => $find->get('value'),
                            'expired' => $find->get('date_end')
                        ];
                    }


                } else {
                    $this->setResponse($this->response->withStatus(406, 'Voucher melebihi batas limit'));
                }


            } else {
                $this->setResponse($this->response->withStatus(406, 'Kode voucher tidak ditemukan.'));
            }

        } else {
            $this->setResponse($this->response->withStatus(406, 'code_voucher is required'));
        }

        $this->set(compact('data'));
    }

    protected function getShipping($couriers, $origin_district_id, $dest_district_id, $weight)
    {
        $out = $this->RajaOngkir->cost(
            $origin_district_id,
            'subdistrict',
            $dest_district_id,
            'subdistrict',
            $couriers,
            $weight
        );

        $result = [];

        if ($out && $out['rajaongkir']['status']['code'] == 200) {
            foreach($out['rajaongkir']['results'] as $key => $val) {
                foreach($val['costs'] as $k => $cost) {
                    $result[] = [
                        'code' => $val['code'],
                        'service' => $cost['service'],
                        'name' => $val['code'] . ' - ' . strtolower($cost['service']),
                        'description' => $cost['description'],
                        'cost' => $cost['cost'][0]['value'],
                        'etd' => $cost['cost'][0]['etd'],
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * payment process
     */
    public function paymentx()
    {

        $this->request->allowMethod(['post', 'put']);
        $trx = new Transaction('ord-0021-x10175');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke 2');


        $payment_type = 'gopay';
        switch ($payment_type) {
            case 'credit_card':
                //for credit card
                $payment = new CreditCard();
                $payment->setToken('441111lSmrlWhaoZtyTjOAscGBrc1118')
                    ->saveToken(true)
                    ->setInstallment('bca', 6)
                    ->setCustomer(
                        'iwaninfo@gmail.com',
                        'Ridwan',
                        'Rumi',
                        '08112823746'
                    )
                    ->setBillingAddress()
                    ->setShippingFromBilling();

                break;

            case 'bca_va':
                //for bca
                $payment = (new BcaVirtualAccount(1111111))
                    ->setSubCompanyCode(1111);
                break;

            case 'mandiri_billpayment':
                $payment = (new MandiriBillPayment());
                break;

            case 'permata_va':
                //for permata
                $payment = (new PermataVirtualAccount())
                    ->setRecipientName('Ridwan');
                break;

            case 'bni_va':
                $payment = new BniVirtualAccount('111111');
                break;

            case 'bca_klikpay':
                $payment = new BcaKlikPay();
                break;

            case 'mandiri_clickpay':
                $token = (new \App\Lib\MidTrans\CreditCardToken())
                    ->setCardNumber('4111 1111 1111 1111')
                    ->request(10000);
                if ($token->status_code == 200) {
                    $payment = new MandiriClickPay($token->token_id, '54321', '000000');
                }
                break;

            case 'gopay':
                $payment = new Gopay('http://php.net');
                break;
        }


        $request = new Request($payment);
        $request->addTransaction($trx);

        $request->setCustomer(
            'iwaninfo@gmail.com',
            'Ridwan',
            'Rumi',
            '0817123123'
        );


        $data = $this->MidTrans->charge($request);


        $this->set(compact('data'));
    }

}
