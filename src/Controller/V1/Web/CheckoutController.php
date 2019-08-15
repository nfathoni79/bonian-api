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
use App\Lib\MidTrans\PaymentRequest;
use App\Lib\MidTrans\Request;
use App\Lib\MidTrans\Transaction;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Utility\Hash;

use Cake\I18n\Time;
use App\Lib\MidTrans\Token;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Cake\Cache\Cache;


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @property \App\Model\Table\CustomerCartDetailsTable $CustomerCartDetails
 * @property \App\Model\Table\CustomerCartsTable $CustomerCarts
 * @property \App\Model\Table\CustomerPointRatesTable $CustomerPointRates
 * @property \App\Model\Table\CustomerVouchersTable $CustomerVouchers
 * @property \App\Model\Table\ProductOptionStocksTable $ProductOptionStocks
 * @property \App\Model\Table\CustomerCartCouponsTable $CustomerCartCoupons
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\CourriersTable $Courriers
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @property \App\Model\Table\CustomerShareProductsTable $CustomerShareProducts
 * @property \App\Model\Table\ShareStatisticsTable $ShareStatistics
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
    protected $customerDetailStatuses = [5];
    protected $cacheKey = null;
    protected $isCreateToken = false;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCards');
        $this->loadModel('CustomerCarts');
        $this->loadModel('CustomerPointRates');
        $this->loadModel('CustomerVouchers');
        $this->loadModel('CustomerCartCoupons');
        $this->loadModel('ProductOptionStocks');
        $this->loadModel('Orders');
        $this->loadModel('Courriers');
        $this->loadModel('Products');
        $this->loadModel('CustomerCartDetails');
        $this->loadModel('Transactions');
        $this->loadModel('CustomerShareProducts');
        $this->loadModel('ShareStatistics');

        $this->loadComponent('RajaOngkir');

    }

    protected function groupCartByBranch($cart, $product_to_couriers, $address)
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
                    if ($address instanceof \App\Model\Entity\CustomerAddrese) {
                        $cart_group_origin[$val['origin_id']]['shipping_options'] = $this->getShipping(
                            implode(':', $courier_group),
                            $val['origin_district_id'],
                            $address->subdistrict_id,
                            $val['weight'] * $val['qty']
                        );
                    } else {
                        $cart_group_origin[$val['origin_id']]['shipping_options'] = [];
                    }

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
                            ],
                            'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                        ],
                        'ProductToCourriers' => [
                            'Courriers'
                        ]
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
            ->where(['CustomerCarts.customer_id' => $this->Authenticate->getId(),'CustomerCarts.status' => 1 ])
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
                    //unset($row->customer_cart_details[$key]->product_option_price_id);
                    //unset($row->customer_cart_details[$key]->product_option_stock_id);

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
        $customer_id = $this->Authenticate->getId();
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


    public function changeAddress()
    {
        $this->request->allowMethod('post');

        $customer_id = $this->Authenticate->getId();
        $address =  $this->Customers->CustomerAddreses->find()
            ->where([
                'customer_id' => $customer_id
            ]);

        if ($this->request->getData('address_id')) {
            $address = $address->where([
                'id' => $this->request->getData('address_id')
            ])->map(function(\App\Model\Entity\CustomerAddrese $row) {
                unset($row->customer_id);
                return $row;
            })
            ->first();
            $cache = Cache::read($this->getStorageKey(), 'checkout');
            $cache['customer_address'] = $address;

            Cache::write($this->getStorageKey(), $cache, 'checkout');
        }


    }
    public function cart()
    {
        $this->request->allowMethod('post');
        $validator = new Validator();

        $getData = $this->request->getData();

        //set customer id
        $getData['customer_id'] = $this->Authenticate->getId();

        $validator->numeric('voucher')
            ->numeric('point')
            ->allowEmptyString('point')
            ->allowEmptyString('kupon')
            ->allowEmptyString('voucher');


        $validator->add('customer_id', 'verify_only', [
            'rule' => function($value) {
               return $this->Customers->find()
                    ->where([
                        'Customers.id' => $value,
                        'Customers.is_verified' => 1,
                    ])
                    ->count() == 1;
            },
            'message' => 'Maaf akun anda belum terverifikasi, silahkan verifikasi akun terlebih dahulu'
        ]);


        $validator->add('point', 'valid_point', [
            'rule' => function($value) {
                $currentPoint = $this->Customers->CustomerBalances->find()
                    ->where([
                        'customer_id' => $this->Authenticate->getId(),
                    ])
                    ->first();
                if ($currentPoint) {
                    return $value <= $currentPoint->get('point') && $value > 0;
                }
            },
            'message' => 'Point yang di input tidak valid.'
        ]);

        //validation voucher
        $validator->add('voucher', 'valid_voucher', [
            'rule' => function($value) {
                $voucher = $this->CustomerVouchers->find()
                    ->where([
                        'CustomerVouchers.customer_id' => $this->Authenticate->getId(),
                        'CustomerVouchers.status' => 1,
                        'CustomerVouchers.id' => $value,
                    ])
                    ->contain([
                        'Vouchers'=> [
                            'VoucherDetails' => [
                                'ProductCategories'
                            ]
                        ]
                    ])
                    ->first();
                if ($voucher) {
                    switch($voucher->get('voucher')->type) {
                        case '1':
                            //check expired
                            /**
                             * @var \Cake\I18n\FrozenTime $expired
                             */
                            $expired = $voucher->get('expired');
                            return ($expired instanceof \Cake\I18n\FrozenTime) ? $expired->gte(Time::now()) : $expired;
                            break;
                        case '2':
                            // CATEGORY IN LIST CART ONLY

                            $categoryIn = [];
                            foreach($voucher['voucher']['voucher_details'] as $k => $v){
                                $categoryIn[] = $v['product_category_id'];
                            }


                            $query = $this->CustomerCarts->find()
                                ->contain(['CustomerCartDetails'])
                                ->where(['CustomerCarts.customer_id' => $this->Authenticate->getId(), 'CustomerCarts.status' => 1])
                                ->first()
                                ->toArray();

                            foreach($query['customer_cart_details'] as $vals){
                                if(in_array($vals['status'], [1,5])){
                                    if(in_array($vals['product_category_id'],$categoryIn )){
                                        return true;
                                        break;
                                    }
                                }
                            }

                            return false;
                            break;
                        default:
                            return true;
                            break;
                    }
                } else {
                    return false;
                }
            },
            'message' => 'voucher yang di input tidak valid.'
        ]);

        //validation kupon
        $validator->add('kupon', 'valid_kupon', [
            'rule' => function($value) {
                $kupon = $this->CustomerCartCoupons->find()
                    ->contain([
//                        'CustomerCarts',
                        'ProductCoupons',
                    ])
                    ->where([
                        'CustomerCartCoupons.customer_cart_id IS NULL',
                        'CustomerCartCoupons.customer_id' => $this->Authenticate->getId(),
                        'CustomerCartCoupons.id' => $value,
                    ])
                    ->first();
                if ($kupon) {
                    return true;
                } else {
                    return false;
                }
            },
            'message' => 'kupon yang di input tidak valid.'
        ]);

        //validation cardid
        $validator
            ->requirePresence('cart', 'create', 'Silahan pilih produk')
            ->hasAtLeast('cart', 1, 'Silahan pilih produk');

        $carts = new Validator();
        $data = [];
        if ($this->request->getData('cart')) {
            foreach($this->request->getData('cart') as $k => $vals){
                $field = new Validator();
                $field
                    ->notBlank('id', 'Silahan pilih produk');
                $field
                    ->notBlank('stock_id', 'Silahan pilih produk');
                $field
                    ->notBlank('qty', 'Silahan pilih produk')
                    ->add('qty', 'valid_qty', [
                        'rule' => function($value,$form) use(&$data){

                            $stockId = $form['data']['stock_id'];

                            $cekStock = $this->ProductOptionStocks->find()
                                ->where(['id' => $stockId])->first();
                            return $cekStock->get('stock') >= $value;

                        },
                        'message' => 'Quantity tidak tersedia'
                    ]);

                $carts->addNested($k, $field);
            }
        }


        $validator->addNested('cart', $carts);


        $error = $validator->errors($getData);

        if (!$error) {
            if ($storage_key = $this->getStorageKey()) {
                //save storage
                Cache::write($storage_key, [
                    'point' => !empty($this->request->getData('point')) ? $this->request->getData('point') : 0,
                    'voucher' => $this->request->getData('voucher'),
                    'kupon' => $this->request->getData('kupon'),
                    'step' => 1
                ], 'checkout');

                foreach($this->request->getData('note') as $k => $v){
                    $query = $this->CustomerCartDetails->query();
                    $query->update()
                        ->set(['comment' => $v])
                        ->where([
                            'id' => $k
                        ])
                        ->execute();
                }

                $customerCart = $this->CustomerCarts->find()
                    ->contain(['CustomerCartDetails'])
                    ->where(['CustomerCarts.customer_id' => $this->Authenticate->getId(), 'CustomerCarts.status' => 1])
                    ->first() ;
                if($customerCart){
                    $updateToActive = $this->CustomerCartDetails->find()
                        ->where(['status' => 5, 'customer_cart_id' => $customerCart->get('id')])
                        ->all();
                    foreach($updateToActive as $vals){
                        $query = $this->CustomerCartDetails->query();
                        $query->update()
                            ->set(['status' => 1])
                            ->where([
                                'id' => $vals['id']
                            ])
                            ->execute();
                    }
                }

                foreach($this->request->getData('cart') as $k => $val){
                    $query = $this->CustomerCartDetails->query();
                    $query->update()
                        ->set(['status' => 5])
                        ->where([
                            'id' => $val['id']
                        ])
                        ->execute();
                }


            } else {
                $this->setResponse($this->response->withStatus(406, 'Invalid cart'));
            }

        } else {
            $this->setResponse($this->response->withStatus(406, 'Cannot process checkout'));
        }
        $this->set(compact('error', 'data'));
    }


    protected function getStorageKey()
    {
        //get current cart_id
        if ($this->cacheKey) {
            return $this->cacheKey;
        }
        $cartEntity = $this->CustomerCarts->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'status' => 1
            ])
            ->first();
        if ($cartEntity) {
            return $this->cacheKey = $this->Authenticate->getId() . '_' . $cartEntity->get('id');
        }
        return $this->Authenticate->getId();
    }


    public function index()
    {
        //list checkout and default customer address
        $data = [];

        //get storage cache
        if (($cache = Cache::read($this->getStorageKey(), 'checkout'))) {


            $customer_id = $this->Authenticate->getId();
            if(empty($cache['customer_address'])){
                $data['customer_address'] = $this->getAddress();
            }

            $data = array_merge($data, $cache);
            /*
            $get_point = $this->Customers->CustomerBalances->find()
                ->where([
                    'customer_id' => $customer_id
                ])
                ->first();

            $data['point'] = (int) $get_point->get('point');
            */


            $product_to_couriers = [];
            $total = 0;
            $cart = $this->getCart(function ($key, \App\Model\Entity\CustomerCart $row) use (&$product_to_couriers) {
                $product_to_couriers[$row->customer_cart_details[$key]->origin_id][] = $row->customer_cart_details[$key]->couriers;
            }, function (\App\Model\Entity\CustomerCart $row) use (&$total) {
                foreach ($row['customer_cart_details'] as $val) {
                    $total += $val['total'];
                }
            });

            $data['gross_total'] = $total;

            //grouping by origin_id
            $cart_group_origin = $this->groupCartByBranch($cart, $product_to_couriers, $data['customer_address']);


            //check if customer using voucher
            $total = $this->usingVoucher($customer_id, $total, $data['voucher'],$cart_group_origin, function(\App\Model\Entity\CustomerVoucher $customerVoucherEntity, $discount) use (&$data) {
                $data['code_voucher'] = $customerVoucherEntity->voucher->code_voucher;
                $data['potongan_voucher'] = $discount;
            });

            //check if customer using kupon
            $total = $this->usingKupon($customer_id, $total, $data['kupon'], function(\App\Model\Entity\CustomerCartCoupon $customerCouponEntity, $discount) use (&$data) {
//                $data['code_kupon'] = $customerVoucherEntity->voucher->code_voucher;
                $data['potongan_kupon'] = $discount;
            });



            $data['total'] = $total - $data['point'];
            $data['carts'] = $cart_group_origin;



        } else {
            $this->setResponse($this->response->withStatus(406, 'invalid step checkout'));
        }
        //$data['cookie'] = json_decode($this->Tools->decrypt($this->request->getCookieParams()['share_product']), true);

//        debug($data);
        $this->set(compact('data', 'error'));

    }

    /**
     * @param $customer_id
     * @param $total
     * @param callable|null $callback
     * @return float|int
     */
    protected function usingKupon($customer_id, $total, $kuponId,  callable $callback = null){

        /**
         * @var \App\Model\Entity\CustomerCartCoupon $CustomerCartCouponEntity
         */
        $customerCartCouponEntity = $this->CustomerCartCoupons->find()
            ->where([
                'CustomerCartCoupons.customer_id' => $customer_id,
//                'CustomerCarts.status' => 1,
                'CustomerCartCoupons.id' => $kuponId,
//                'Vouchers.status' => 1,
            ])
            ->contain([
//                'CustomerCarts',
                'ProductCoupons',
            ])
            ->first();
        if ($customerCartCouponEntity) {
            $discount = 0;
            $discount = $customerCartCouponEntity->product_coupon->price;
            $total = $total - $discount;

            if (is_callable($callback)) {
                call_user_func_array($callback, [$customerCartCouponEntity, $discount]);
            }
        }
        return $total;
    }

    protected function usingVoucher($customer_id, $total, $voucherID, $dataCart, callable $callback = null)
    {
        /**
         * @var \App\Model\Entity\CustomerVoucher $customerVoucherEntity
         */
        $customerVoucherEntity = $this->CustomerVouchers->find()
            ->where([
                'CustomerVouchers.customer_id' => $customer_id,
                'CustomerVouchers.status' => 1,
                'CustomerVouchers.id' => $voucherID,
//                'Vouchers.status' => 1,
            ])
            ->contain([
                'Vouchers' => [
                    'VoucherDetails'
                ]
            ])
            ->orderDesc('CustomerVouchers.id')
            ->first();
        if ($customerVoucherEntity) {
            $discount = 0;
            switch($customerVoucherEntity->voucher->type) {
                case '1':
                    $discount = $customerVoucherEntity->voucher->percent / 100 * $total;
                    $discount = $discount > $customerVoucherEntity->voucher->value ? $customerVoucherEntity->voucher->value : $discount;
                    $total = $total - $discount;
                    break;
                case '2':
                    $keyCategoryInVoucher = [];
                    foreach($customerVoucherEntity->voucher->voucher_details as $vals){
                        $keyCategoryInVoucher[] = $vals['product_category_id'];
                    }
                    $totalInCategory = 0;
                    foreach($dataCart as $branch_id => $value) {
                        foreach($value['data'] as $vals) {
                            if(in_array($vals['product_category_id'], $keyCategoryInVoucher )){
                                $totalInCategory += $vals['total'];
                            }
                        }
                    }
                    $discount = $customerVoucherEntity->voucher->percent / 100 * $totalInCategory;
                    $discount = $discount > $customerVoucherEntity->voucher->value ? $customerVoucherEntity->voucher->value : $discount;
                     $total = $total - $discount;
                    break;
                case '3': // private voucher
                    $discount = $customerVoucherEntity->voucher->percent / 100 * $total;
                    $discount = $discount > $customerVoucherEntity->voucher->value ? $customerVoucherEntity->voucher->value : $discount;
                     $total = $total - $discount;
                    break;
            }

            if (is_callable($callback)) {
                call_user_func_array($callback, [$customerVoucherEntity, $discount]);
            }

        }

        return $total;
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
                    $customerEntity = $this->Customers->get($this->Authenticate->getId());
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


    public function createToken()
    {
        $this->isCreateToken = true;
        $amount = $this->process();
        $customer_id = $this->Authenticate->getId();
        $customer_card_entity = $this->CustomerCards->find()
            ->where([
                'customer_id' => $customer_id,
                'id' => $this->request->getData('card_id')
            ])
            ->first();

        if ($customer_card_entity) {
            $credit_card_token = new CreditCardToken();
            $token = $credit_card_token->setToken($customer_card_entity->get('token'))
                ->setCvv($this->request->getData('cvv'))
                ->setSecure(true)
                ->request($amount);

            if ($token->status_code != 200) {
                $this->setResponse($this->response->withStatus(406, 'Gagal verifikasi cvv'));
            }
        }





        $this->set(compact('token', 'amount'));
    }

    public function oke()
    {
        //get customer
        $customerEntity = null;
        try {
            $customerEntity = $this->Customers->get($this->Authenticate->getId());
        } catch(\Exception $e) {

        }

        $payment = new CreditCard();
        $payment->setToken('xxxxxx')
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

        $request = new Request($payment);
        //$request = $request->toObject();

        $trx = new Transaction('cccccc');

        $trx->addItem('point', 5000, 1, 'Using Point Customer');
        $request->addTransaction($trx);

        $this->MidTrans->charge($request);

        $this->set(compact('payment', 'request'));
    }

    public function gopayStatus()
    {
        $this->request->allowMethod('post');
        if ($transaction_id = $this->request->getData('transaction_id')) {
            $transactionEntity = $this->Transactions->find()
                ->where([
                    'Transactions.transaction_id' => $transaction_id,
                    'Transactions.payment_type' => 'gopay'
                ])
                ->first();

            if ($transactionEntity) {
                $raw_response = $transactionEntity->get('raw_response');
                if ($raw_response) {
                    $response = json_decode($raw_response, true);
                    if (isset($response['actions'])) {
                        foreach($response['actions'] as $val) {
                            if ($val['name'] == 'get-status') {
                                try {
                                    $r = $this->MidTrans->makeRequest()
                                        ->get($val['url'])
                                        ->getBody()
                                        ->getContents();
                                    $data = json_decode($r, true);
                                } catch(\GuzzleHttp\Exception\ClientException $e) {}


                                break;
                            }
                        }
                    }
                }

            } else {
                $this->setResponse($this->response->withStatus(404, 'Transaction not found'));
            }
        }

        $this->set(compact('data'));
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

        $customer_id = $this->Authenticate->getId();

        $validator = new Validator();

        /*$shippingValidation = new Validator();
        $shippingValidation->requirePresence('code')
            ->inList('code', ['jne', 'jnt', 'tiki', 'pos'])
            ->notBlank('code')
            ->requirePresence('service')
            ->notBlank('service');

        $validator->addNestedMany('shipping', $shippingValidation);*/

//        $validator->add('use_point', 'valid_point', [
//            'rule' => function($value) use($customer_id) {
//                $currentPoint = $this->Customers->CustomerBalances->find()
//                    ->where([
//                        'customer_id' => $customer_id,
//                    ])
//                    ->first();
//                if ($currentPoint) {
//                    return $value <= $currentPoint->get('point') && $value > 0;
//                }
//            },
//            'message' => 'Point yang di input tidak valid.'
//        ]);

        $shippingValidation = new Validator();
        $shippingValidation->requirePresence('code')
            ->inList('code', ['JNE', 'JNT', 'TIKI', 'POS', 'J&T'])
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
            ->where(['CustomerCarts.customer_id' => $this->Authenticate->getId(),'CustomerCarts.status' => 1 ])
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
                /*'credit_card',
                'mandiri_billpayment',
                'bca_va',
                'permata_va',
                'bni_va',
                'bca_klikpay',
                'mandiri_clickpay',
                'gopay',*/
                'online_payment',
                'wallet'
            ]);

        if (($payment_method = $this->request->getData('payment_method')) == 'credit_card') {
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
                ])
                ->notBlank('token');

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

        } else if (($payment_method = $this->request->getData('payment_method')) == 'wallet') {
            $validator
                ->notBlank('password', 'Password tidak boleh kosong.')
                ->add('password', 'check_password', [
                    'rule' => function($value) use($customer_id) {
                        $passwordEntity = $this->Customers->find()
                                ->select(['password'])
                                ->where([
                                    'id' => $customer_id,
                                ])
                                ->first();
                        if ($passwordEntity) {
                            return (new DefaultPasswordHasher())->check($value, $passwordEntity->get('password'));
                        }
                    },
                    'message' => 'Password anda salah.'
                ]);
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
            /**
             * @var \App\Model\Entity\CustomerCartDetail[] $cartDetailEntities
             */
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


                $cache = Cache::read($this->getStorageKey(), 'checkout');

                $invoice = strtoupper(date('ymdHs') . Security::randomString(4));
                $addresses = $this->getAddress($address_id);
                $gross_total = 0;
                $use_point = (int)$cache['point'];

                $trx = new Transaction($invoice);


                $order_detail_entities = [];
                $order_detail_product_entities = [];
                $shipping_cost = 0;
                foreach ($cart as $origin_id => $item) {
                    $subtotal = 0;
                    foreach ($item['data'] as $val) {
                        $trx->addItem($val['product_id'], $val['price'] + $val['add_price'], $val['qty'], $val['name']);
                        $subtotal += $val['price'] * $val['qty'];
                        $gross_total += ($val['price'] + $val['add_price']) * $val['qty'];
                        //debug($val);
                        $order_detail_product_entities[$origin_id][] = $this
                            ->Orders
                            ->OrderDetails
                            ->OrderDetailProducts
                            ->newEntity([
                                'product_id' => $val['product_id'],
                                'qty' => $val['qty'],
                                'price' => $val['price'] + $val['add_price'],
                                'total' => ($val['price'] + $val['add_price']) * $val['qty'],
                                'in_flashsale' => $val['in_flashsale'],
                                'in_groupsale' => $val['in_groupsale'],
                                'product_option_stock_id' => $val['stock_id'],
                                'product_option_price_id' => $val['price_id'],
                                'comment' => $val['comment'],
                                'bonus_point' => $val['totalpoint'],
                                'product_category_id' => $val['product_category_id']
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


                                    //$gross_total += $shipping_option['cost'];
                                    $shipping_cost += $shipping_option['cost']; // division shipping later
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
                                        'shipping_etd' => $shipping_option['etd'],
                                        'order_status_id' => 1,
                                        'awb' => ''
                                    ]);
                                }
                            }
                        }
                    }
                }


                $total = $gross_total;

                /**
                 * @var \App\Model\Entity\CustomerVoucher $customerVoucherEntity
                 */
                $customerVoucherEntity = null;
                //check voucher claim

                $discountVoucher = 0;
                $total = $this->usingVoucher($customer_id, $total, $cache['voucher'], $cart, function(\App\Model\Entity\CustomerVoucher $voucherEntity, $discount) use (&$trx, &$customerVoucherEntity, &$discountVoucher) {
                    $trx->addItem(
                        'voucher-' . $voucherEntity->get('id'),
                        -$discount,
                        1,
                        'Using voucher ' . $voucherEntity->voucher->code_voucher
                    );
                    $discountVoucher = $discount;
                    $customerVoucherEntity = $voucherEntity;
                });

                $customerCouponEntity = null;
                $discountCoupon = 0;
                $total = $this->usingKupon($customer_id, $total, $cache['kupon'], function(\App\Model\Entity\CustomerCartCoupon $couponEntity, $discount) use (&$trx, &$discountCoupon, &$customerCouponEntity) {
                    $trx->addItem(
                        'coupon-' . $couponEntity->get('id'),
                        -$discount,
                        1,
                        'Using coupon product_id: ' . $couponEntity->product_coupon->product_id
                    );
                    $discountCoupon = $discount;
                    $customerCouponEntity = $couponEntity;
                });

                if ($use_point > 0) {

                    $pointRateEntity = $this->CustomerPointRates->find()
                        ->orderDesc('CustomerPointRates.id')
                        ->first();

                    if ($pointRateEntity) {
                        $use_point = ($use_point / intval($pointRateEntity->get('point'))) * intval($pointRateEntity->get('value'));
                    }

                    $total = $total - $use_point;
                    $trx->addItem('point', -$use_point, 1, 'Using Point Customer');
                }

                $total += $shipping_cost;
                $gross_total += $shipping_cost;


                $orderEntity = $this->Orders->newEntity([
                    'invoice' => $invoice,
                    'customer_id' => $customer_id,
                    'province_id' => $addresses->get('province_id'),
                    'city_id' => $addresses->get('city_id'),
                    'subdistrict_id' => $addresses->get('subdistrict_id'),
                    'address' => $addresses->get('address'),
                    'recipient_name' => $addresses->get('recipient_name'),
                    'recipient_phone' => $addresses->get('recipient_phone'),
                    'use_point' => $use_point,
                    'gross_total' => $gross_total,
                    'discount_voucher' => $discountVoucher,
                    'discount_kupon' => $discountCoupon,
                    'total' => $total,
                    'voucher_id' => $customerVoucherEntity ? $customerVoucherEntity->get('voucher_id') : null,
                    'product_coupon_id' => $customerCouponEntity ? $customerCouponEntity->get('product_coupon_id') : null
                ]);

                //get customer
                $customerEntity = null;
                try {
                    $customerEntity = $this->Customers->get($this->Authenticate->getId());
                } catch(\Exception $e) {

                }

                $payment_method = $this->request->getData('payment_method');

                $data['payment_method'] = $payment_method;
                $data['payment_amount'] = $trx->getAmount();
                $data['trx'] = $trx->toObject();

                if ($this->isCreateToken) {
                    return $trx->getAmount();
                }

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




                        } else {

                            /*$credit_card_token = new CreditCardToken(
                                $this->request->getData('card_number'),
                                $this->request->getData('card_exp_month'),
                                $this->request->getData('card_exp_year'),
                                $this->request->getData('cvv')
                            );

                            $token = $credit_card_token->setSecure(true)
                                ->request($trx->getAmount());*/
                        }

                        /*$data[$payment_method] = array_filter([
                            'redirect_url' => $token->redirect_url,
                            'token' => $token->token_id,
                            'bank' => $token->bank
                        ]);*/


                        break;

                    case 'bca_va':
                        //for bca
                        $va_number_fixed = $customerEntity->get('phone')
                            ? preg_replace('/^\+62/i', '0', $customerEntity->get('phone')) : null;

                        $va_number_fixed = null; //force null

                        if ($va_number_fixed) {
                            $payment = (new BcaVirtualAccount($va_number_fixed))
                                ->setSubCompanyCode(1111);
                        } else {
                            $payment = (new BcaVirtualAccount(rand(111111,999999)))
                                ->setSubCompanyCode(1111);
                        }

                        break;

                    case 'mandiri_billpayment':
                        $payment = (new MandiriBillPayment());
                        break;

                    case 'permata_va':
                        //for permata
                        $full_name = trim($customerEntity->get('first_name') . ' ' . $customerEntity->get('last_name'));
                        $full_name = empty($full_name) ? $customerEntity->get('username') : $full_name;
                        $payment = (new PermataVirtualAccount())
                            ->setRecipientName($full_name);
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
                        $payment = new Gopay($this->request->getData('callback_url'));
                        break;

                    case 'wallet':
                        $payment = null;
                        $balance = 0;
                        $getBalance = $this->Customers->CustomerBalances->find()
                            ->where([
                                'customer_id' => $this->Authenticate->getId()
                            ])
                            ->first();
                        if ($getBalance) {
                            $balance = $getBalance->get('balance');
                        }
                    break;
                    case 'online_payment':
                        $payment = null;
                        break;

                    default:
                        $payment = null;
                        break;
                }


                $request = null;



                if ($payment instanceof PaymentRequest) {
                    $request = new Request($payment);
                    $request->addTransaction($trx);


                    if (!$request->isCreditCard()) {
                        $request->setCustomer(
                            $customerEntity->get('email'),
                            $customerEntity->get('first_name'),
                            $customerEntity->get('last_name'),
                            $customerEntity->get('phone')
                        );
                    }
                }



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
                                //process order shipping detail
                                $shippingDetailEntity = $this->Orders->OrderDetails->OrderShippingDetails->newEntity([
                                    'order_detail_id' => $detailEntity->id,
                                    'status' => 1,
                                    'note' => ''
                                ]);

                                $this->Orders->OrderDetails->OrderShippingDetails->save($shippingDetailEntity);

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
                                            4, //penjualan
                                            -$detailProductEntity->get('qty'),
                                            ''
                                        );

                                        //mark status cart detail
                                        if (is_array($cartDetailEntities)) {
                                            foreach($cartDetailEntities as $key => $cart_val) {
                                                if ($cart_val->product_option_price_id == $detailProductEntity->product_option_price_id &&
                                                    $cart_val->product_option_stock_id == $detailProductEntity->product_option_stock_id) {
                                                    $cartDetailEntities[$key]->status = 4;
                                                }
                                            }
                                        }

                                        /*$this
                                            ->Customers
                                            ->CustomerMutationPoints
                                            ->saving(
                                                $customer_id,
                                                3,
                                                intval($detailProductEntity->bonus_point),
                                                'bonus point belanja'
                                            );*/

                                    } else {
                                        $process_save_order = false;
                                    }
                                }
                            } else {
                                $process_save_order = false;
                            }

                        }

                        //process save share products
                        if ($share_product = $this->request->getCookie('share_product')) {
                            $share_product_object = json_decode($this->Tools->decrypt($share_product), true);
                            if ($share_product_object && is_array($share_product_object) && isset($share_product_object['customer_id'])) {
                                if ($share_product_object['customer_id'] != $orderEntity->customer_id) {
                                    $checkExists = $this->ShareStatistics->find()
                                        ->where([
                                            'customer_id' => $share_product_object['customer_id'],
                                            'product_id' => $share_product_object['product_id'],
                                            'clicked' => 1
                                        ])->count();

                                    if ($checkExists > 0) {
                                        $shareProductEntity = $this->CustomerShareProducts->newEntity([
                                            'customer_id' => $share_product_object['customer_id'],
                                            'product_id' => $share_product_object['product_id'],
                                            'order_id' => $orderEntity->id,
                                            'percentage' => Configure::read('sharing_percentage', 0.01)
                                        ]);
                                        $this->CustomerShareProducts->save($shareProductEntity);
                                    }
                                }
                            }
                        }


                        if (is_array($cartDetailEntities)) {
                            /**
                             * @var \App\Model\Entity\CustomerCartDetail[] $cartDetailEntities
                             */

                            $total_selected = 0;
                            foreach($cartDetailEntities as $cartDetailEntity) {
                                //$cartDetailEntity->set('status', 4);
                                if ($cartDetailEntity->status == 4) {
                                    $this->CustomerCarts->CustomerCartDetails->save($cartDetailEntity);
                                    $total_selected++;
                                }
                            }

                            $exists_customer_cart_detail = $this->CustomerCarts->CustomerCartDetails->find()
                                ->where([
                                    'CustomerCarts.customer_id' => $this->Authenticate->getId(),
                                    'CustomerCarts.status' => 1, //status is active,
                                    'CustomerCartDetails.status' => 1
                                ])
                                ->contain([
                                    'CustomerCarts'
                                ])
                                ->count();

                            if ($exists_customer_cart_detail == 0) {
                                $cartEntity->set('status', 3);
                                $this->CustomerCarts->save($cartEntity);
                            }
                        }


                        if ($customerCouponEntity instanceof \App\Model\Entity\CustomerCartCoupon) {
                            $customerCouponEntity->set('customer_cart_id', $cartEntity->get('id'));
                            $this->CustomerCartCoupons->save($customerCouponEntity);
                        }
                        //process mutation point here
                        if ($use_point > 0) {
                            $this
                                ->Customers
                                ->CustomerMutationPoints
                                ->saving(
                                    $customer_id,
                                    1,
                                    - intval($use_point),
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
                    if ($request instanceof \App\Lib\MidTrans\Request) {
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
                                        $this->setResponse($this->response->withStatus(406, 'Proses payment gagal 1'));
                                        $process_payment_charge = false;
                                        break;
                                }

                                if (isset($charge['payment_type'])) {
                                    switch($charge['payment_type']) {
                                        case 'bank_transfer':
                                            if (isset($charge['va_numbers'])) {
                                                foreach($charge['va_numbers'] as $va) {
                                                    $charge['va_number'] = $va['va_number'];
                                                    $charge['bank'] = $va['bank'];
                                                }
                                            } else if (isset($charge['permata_va_number'])) {
                                                $charge['va_number'] = $charge['permata_va_number'];
                                                $charge['bank'] = 'permata';
                                            }
                                            break;
                                    }
                                }

                            }

                        } catch(\Exception $e) {
                            $this->Orders->getConnection()->rollback();
                            $process_payment_charge = false;

                        }
                    }
                    else if ($payment_method == 'online_payment') {
                        //midtrans snap here

                        $charge = [
                            'transaction_id' => Text::uuid(),
                            'transaction_time' => date('Y-m-d H:i:s'),
                            'gross_amount' => $orderEntity->total,
                        ];

                        \Veritrans_Config::$serverKey = Configure::read('Midtrans.serverKey');
                        //\Veritrans_Config::$isSanitized = true;
                        \Veritrans_Config::$is3ds = true;

                        $data_transactions = $trx->toObject();
                        $customer_details = [
                            'first_name'    => $customerEntity->get('first_name'),
                            'last_name'     => $customerEntity->get('last_name'),
                            'email'         => $customerEntity->get('email'),
                            'phone'         => $customerEntity->get('phone'),
                            'billing_address'  => isset($addresses) ? $addresses->get('address') : '',
                            'shipping_address' => isset($addresses) ? $addresses->get('address'): ''


                        ];

                        // Fill transaction details
                        $transaction = array(
                            'enabled_payments' => [],
                            'transaction_details' => $data_transactions['transaction_details'],
                            'customer_details' => $customer_details,
                            'item_details' => $data_transactions['item_details'],
                        );

                        try {
                            $data['snap_token'] = \Veritrans_Snap::getSnapToken($transaction);
                        } catch(\Exception $e) {
                            $this->setResponse($this->response->withStatus(406, $e->getMessage()));
                            $process_payment_charge = false;
                        }
                    }
                    else if ($payment_method == 'wallet') {
                        $charge = [
                            'transaction_id' => Text::uuid(),
                            'transaction_time' => date('Y-m-d H:i:s'),
                            'transaction_status' => 'success',
                            'status_code' => 200,
                            'fraud_status' => 'accept',
                            'gross_amount' => $orderEntity->total,
                            'payment_type' => 'wallet'
                        ];
                        if (isset($balance) && $balance < $trx->getAmount()) {
                            $data['payment_status'] = 'failed';
                            $process_payment_charge = false;
                        }
                    }
                }





                if ($process_save_order && $process_payment_charge) {

                    $transactionEntity = $this->Transactions->newEntity($charge);
                    if ($transactionEntity->payment_type && $orderEntity->get('id')) {
                        $transactionEntity->set('raw_response', json_encode($charge));
                        $transactionEntity->set('order_id', $orderEntity->get('id'));
                        $this->Transactions->save($transactionEntity);
                        if ($payment_method == 'wallet') {
                            //send event
                            $orderEntity->payment_status = 2;
                            $this->Orders->save($orderEntity);

                            $data['payment'] = [
                                'order_id' => $orderEntity->invoice
                            ];

                            $this->Orders->Customers->CustomerMutationAmounts->saving(
                                $orderEntity->customer_id,
                                1,
                                -$orderEntity->total,
                                'Transaksi untuk invoice: ' . $orderEntity->invoice
                            );

                            $this->getEventManager()->dispatch(new Event('Controller.Ipn.success', $this, [
                                'transactionEntity' => $transactionEntity,
                                'orderEntity' => null //set null and on event to get again
                            ]));
                        }

                        Cache::delete($this->getStorageKey(), 'checkout');
                    }

                    $this->Orders->getConnection()->commit();
                } else {
                    switch ($payment_method) {
                        case 'credit_card':
                            $this->setResponse($this->response->withStatus(406, 'Proses pembayaran Gagal, pastikan anda menginput PIN yang tepat, Jika masih berlanjut silahkan hubungi Bank Kartu anda'));
                        break;
                        case 'wallet':
                            $this->setResponse($this->response->withStatus(406, 'Proses pembayaran gagal, Saldo tidak cukup silahkan pilih metode pembayaran lain.'));
                        break;
                    }

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
        $customer_id = $this->Authenticate->getId();
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
        $rename = [
            'CTC' => 'reg',
            'CTCYES' => 'yes',
        ];

        if ($out && $out['rajaongkir']['status']['code'] == 200) {
            foreach($out['rajaongkir']['results'] as $key => $val) {
                foreach($val['costs'] as $k => $cost) {
                    if(array_key_exists($cost['service'],$rename )) {
                        $label = $rename[$cost['service']];
                    }else{
                        $label = $cost['service'];
                    }
                    $val['code'] = strtoupper(str_replace('J&T', 'JNT', $val['code']));
                    $result[] = [
                        'code' => $val['code'],
                        'service' => $cost['service'],
                        'name' => $val['code'] . ' - ' . strtolower($label),
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
