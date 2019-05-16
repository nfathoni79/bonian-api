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
use Cake\I18n\Time;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Customers controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @property \App\Controller\Component\RajaOngkirComponent $RajaOngkir
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class OrdersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Orders');
        $this->loadModel('Transactions');
        $this->loadComponent('RajaOngkir');
    }

    /**
     * list all address
     */
    public function index()
    {
        $status_payment = [
            'semua' => '0',
            'pending' => '1',
            'success' => '2',
            'failed' => '3',
            'expired' => '4',
            'refunde' => '5',
            'cancel' => '6',
        ];

        $orders = $this->Orders->find()
            ->contain([
                'Transactions' => [
                    'fields' => [
                        'order_id',
                        'transaction_time',
                        'transaction_status',
                        'fraud_status',
                        'gross_amount',
                        'currency',
                        'payment_type',
                        'va_number',
                        'masked_card',
                        'card_type',
                        'created',
                        'modified'
                    ]
                ],
                'OrderDetails' => [
                    'Branches',
                    'OrderStatuses',
                    'OrderShippingDetails'
                ],
                'OrderDigitals' => [
//                    'OrderStatuses'
                ],
                'Vouchers' => [
                    'fields' => [
                        'id',
                        'code_voucher'
                    ]
                ],
                'Provinces',
                'Cities',
                'Subdistricts'

            ])
            ->where([
                'Orders.customer_id' => $this->Authenticate->getId()
            ]);


        $orders
            ->orderDesc('Orders.id')
            ;

        if(!empty($this->request->getQuery('search'))){
            $orders->where(['Orders.invoice' => $this->request->getQuery('search')]);
        }

        if(($this->request->getQuery('status') != 'semua') && ($this->request->getQuery('status'))){
            $orders->where([
                'Orders.payment_status' => $status_payment[$this->request->getQuery('status')]
            ]);
        }

        if(!empty($this->request->getQuery('start')) && !empty($this->request->getQuery('end'))){
            $orders->where(function (\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('start'))).' 00:00:00')
                    ->lte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('end'))).' 23:59:59');
            });
        }


        $data = $this->paginate($orders, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
            ->map(function (\App\Model\Entity\Order $row) {
                //$row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;

                unset($row->customer_id);

                return $row;
            });

        $this->set(compact('data'));
    }

    public function view($invoice)
    {
        $shipping_status = [
          '1' => 'Menunggu Pembayaran',
          '2' => 'Diproses',
          '3' => 'Dikirim',
          '4' => 'Selesai',
        ];
        $data = $this->Orders->find()
            ->contain([
                'Transactions' => [
                    'fields' => [
                        'order_id',
                        'transaction_time',
                        'transaction_status',
                        'fraud_status',
                        'gross_amount',
                        'currency',
                        'payment_type',
                        'va_number',
                        'bank',
                        'masked_card',
                        'card_type',
                    ]
                ],
                'Vouchers' => [
                    'fields' => [
                        'id',
                        'code_voucher'
                    ]
                ],
                'Provinces',
                'Cities',
                'Subdistricts',
                'OrderDetails' => [
                    'Branches',
                    'OrderStatuses',
                    'OrderDetailProducts' => [
                        'Products' => [
                            'ProductImages' => [
                                'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                            ]
                        ],
                        'ProductOptionPrices' => [
                            'ProductOptionValueLists' => [
                                'Options',
                                'OptionValues'
                            ],
                            'ProductOptionStocks'
                        ],
                    ],
                    'OrderShippingDetails'
                ]

            ])
            ->where([
                'Orders.customer_id' => $this->Authenticate->getId(),
                'Orders.invoice' => $invoice
            ])
            ->map(function(\App\Model\Entity\Order $row) use(&$shipping_status) {
                $row->details = [];
                foreach($row->order_details as $key => $val) {
                    $row->details[$key] = [
                        'id' => $val['id'],
                        'awb' => $val['awb'],
                        'shipping_code' => $val['shipping_code'],
                        'shipping_service' => $val['shipping_service'],
                        'shipping_weight' => $val['shipping_weight'],
                        'shipping_cost' => $val['shipping_cost'],
                        'total' => $val['total'],
                        'status' => $val['order_status']['name'],
                        'origin_name' => $val['branch']['name'],
                        'origin_address' => $val['branch']['address'],
                        'products' => []
                    ];

                    foreach($val->order_shipping_details as  $k => $shipping){
                        $row->details[$key]['shipping_status']['code'] = $shipping['status'];
                        $row->details[$key]['shipping_status']['name'] = $shipping_status[$shipping['status']];
                    }

                    foreach($val->order_detail_products as $k => $product) {

                        $variant = [];
                        foreach($product['product_option_price']['product_option_value_lists'] as $list){
                            $variant[] = $list['option']['name'] .' : '. $list['option_value']['name'];
                        }
                        $weight = 0;
                        foreach($product['product_option_price']['product_option_stocks'] as $list){
                            $weight =  $list['weight'];
                        }

                        $row->details[$key]['products'][$k] = [
                            'product_id' => $product['product_id'],
                            'name' => $product['product']['name'],
                            'slug' => $product['product']['slug'],
                            'model' => $product['product']['model'],
                            'code' => $product['product']['code'],
                            'point' => $product['product']['point'],
                            'sku' => $product['product_option_price']['sku'],
                            'weight' => $weight,
                            'qty' => $product['qty'],
                            'price' => $product['price'],
                            'total' => $product['total'],
                            'comment' => $product['comment'],
                            'rating' => $product['product']['rating'],
                            'rating_count' => $product['product']['rating_count'],
                            'view' => $product['product']['view'],
                            'variant' => implode(', ', $variant),
                            'images' => Hash::extract($product['product']['product_images'], '{n}.name')
                        ];

                    }
                }

                unset($row->order_details);

                return $row;
            })
            ->first();

        $this->set(compact('data'));
    }


    public function getPendingInvoice($invoice)
    {
        $data = $this->Transactions->find()
            ->contain([
                'Orders' => [
                    'OrderDigitals'
                ]
            ])
            ->where([
                'Orders.customer_id' => $this->Authenticate->getId(),
                'Orders.invoice' => $invoice,
                'Orders.payment_status' => 1,
            ])
            ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->isNotNull('va_number');
            })
            ->map(function(\App\Model\Entity\Transaction $row) {
                return $row;
            })
            ->first();

        if (!$data) {
            $this->setResponse($this->response->withStatus(404, 'Invoice not found'));
        }

        $this->set(compact('data'));
    }

    public function getShipping(){
        $data = $this->RajaOngkir->waybill($this->request->getData('awb'),$this->request->getData('courier'));

        $this->set(compact('data'));

    }

}
