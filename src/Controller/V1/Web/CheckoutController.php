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


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @property \App\Model\Table\CustomerCartsTable $CustomerCarts
 * @property \App\Controller\Component\RajaOngkirComponent $RajaOngkir
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CheckoutController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCards');
        $this->loadModel('CustomerCarts');

        $this->loadComponent('RajaOngkir');

    }


    public function index()
    {
        //list checkout and default customer address
        $data = [];
        $customer_id = $this->Auth->user('id');

        $data['customer_address'] = $this->Customers->CustomerAddreses->find()
            ->where([
                'customer_id' => $customer_id
            ])
            ->orderDesc('is_primary')
            ->map(function(\App\Model\Entity\CustomerAddrese $row) {
                unset($row->customer_id);
                return $row;
            })
            ->first();


        $product_to_couriers = [];
        $cart = $this->CustomerCarts->find()
            ->contain(
                'CustomerCartDetails', function (\Cake\ORM\Query $q) {
                return $q
                    ->where(['CustomerCartDetails.status IN ' => [1, 2, 3]]);
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
            ->where(['CustomerCarts.customer_id' => $customer_id,'CustomerCarts.status' => 1 ])
            ->map(function (\App\Model\Entity\CustomerCart $row) use(&$product_to_couriers) {
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
                    $product_to_couriers[$row->customer_cart_details[$key]->origin_id][] = $couriers;
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
                }
                unset($row->id);
                unset($row->customer_id);
                unset($row->status);
                unset($row->created);
                unset($row->modified);
                return $row;
            })
            ->first();

        //grouping by origin_id
        $cart_group_origin = [];
        if ($cart['customer_cart_details']) {
            foreach($cart['customer_cart_details'] as $key => $val) {
                if (!array_key_exists($val['origin_id'], $cart_group_origin)) {
                    $courier_group = $product_to_couriers[$val['origin_id']][0];
                    if (count($product_to_couriers[$val['origin_id']]) > 1) {
                        $courier_group = call_user_func_array('array_intersect', $product_to_couriers[$val['origin_id']]);
                    }

                    $cart_group_origin[$val['origin_id']]['origin'] = $val['origin'];
                    $cart_group_origin[$val['origin_id']]['total_weight'] = $val['weight'] * $val['qty'];
                    $cart_group_origin[$val['origin_id']]['shipping_options'] = $this->getShipping(
                        implode(':', $courier_group),
                        $val['origin_district_id'],
                        $data['customer_address']->subdistrict_id,
                        $val['weight'] * $val['qty']
                    );
                    $cart_group_origin[$val['origin_id']]['data'][] = $val;

                } else {
                    $cart_group_origin[$val['origin_id']]['total_weight'] += $val['weight'] * $val['qty'];
                    $cart_group_origin[$val['origin_id']]['data'][] = $val;

                }

            }
        }

        $data['carts'] = $cart_group_origin;

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
    public function payment()
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
