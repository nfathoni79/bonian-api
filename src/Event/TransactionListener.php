<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 06/05/2019
 * Time: 9:51
 */

namespace App\Event;
use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use Cake\Event\Event;
use App\Controller\Component\SepulsaComponent;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\Number;
use Cake\Log\Log;
use Cake\ORM\Locator\TableLocator;

/**
 * Class TransactionListener
 * @package App\Event
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @property \App\Model\Table\OrderShippingDetailsTable $OrderShippingDetails
 * @property \App\Model\Table\ProductRatingsTable $ProductRatings
 *
 * @property \App\Controller\Component\MailerComponent $Mailer
 * @property \App\Controller\Component\SepulsaComponent $Sepulsa
 * @property \App\Controller\Component\NotificationComponent $Notification
 * @property \App\Controller\Component\ChatKitComponent $ChatKit
 */
class TransactionListener implements EventListenerInterface
{
    protected $Sepulsa = null;

    public function __construct()
    {
        $this->Sepulsa = new SepulsaComponent(new ComponentRegistry());
    }

    public function implementedEvents()
    {
        return [
            'Controller.Ipn.success' => 'success',
            'Controller.Ipn.expired' => 'expired',
        ];
    }

    public function success(Event $event)
    {
        // Code to update transaction

        $subject = $event->getSubject();
        /**
         * @var \App\Model\Entity\Transaction $transactionEntity
         */
        $transactionEntity = $event->getData('transactionEntity');



        /**
         * @var \App\Model\Entity\Order $orderEntity
         */
        $orderEntity = $event->getData('orderEntity');


        if (property_exists($subject, 'Orders')) {
            $this->Orders = $subject->Orders;

            if (property_exists($subject, 'Mailer')) {
                $this->Mailer = $subject->Mailer;
            }

            if (property_exists($subject, 'Notification')) {
                $this->Notification = $subject->Notification;
            }


            if (!$orderEntity) {
                $orderEntity = $this->Orders->find()
                    ->contain([
                        'Customers',
                        'OrderDetails' => [
                            'OrderShippingDetails',
                            'OrderDetailProducts' => [
                                'Products'
                            ]
                        ],
                        'OrderDigitals' => [
                            'DigitalDetails'
                        ],
                    ])
                    ->where([
                        'Orders.id' => $transactionEntity->order_id
                    ])
                    ->first();
            }


            /**
             * @var \App\Model\Entity\Order $orderEntity
             */
            /*$orderEntity = $this->Orders->find()
                ->where([
                    'Orders.id' => $transactionEntity->get('order_id')
                ])
                ->contain([
                    'OrderDigitals' => [
                        'DigitalDetails'
                    ],
                    'OrderDetails' => [
                        'OrderDetailProducts'
                    ],
                    'Customers'
                ])
                ->first();*/
            $transaction_digital_process_status = true;

            if ($orderEntity && $orderEntity->order_digital instanceof \App\Model\Entity\OrderDigital) {
                if ($orderEntity->order_digital->digital_detail instanceof \App\Model\Entity\DigitalDetail) {

                    switch ($orderEntity->order_digital->digital_detail->type) {
                        case 'mobile':
                            try {
                                $pulsa = $this->Sepulsa->createMobileTransaction(
                                    $orderEntity->order_digital->customer_number,
                                    $orderEntity->order_digital->digital_detail->code,
                                    $orderEntity->invoice
                                );

                                $orderEntity->order_digital->set('raw_response', json_encode($pulsa));
                                $orderEntity->order_digital->set('status', 99);
                                $this->Orders->OrderDigitals->save($orderEntity->order_digital);


                            } catch(\GuzzleHttp\Exception\ClientException $e) {
                                //debug($e->getMessage());

                                $transaction_digital_process_status = false;
                                Log::info($e->getMessage(), ['scope' => ['sepulsa']]);

                                //refund to saldo
                                $this->Orders->getConnection()->begin();
                                if ($this->Orders->Customers->CustomerMutationAmounts->saving(
                                    $orderEntity->customer_id,
                                    2,
                                    $orderEntity->total,
                                    'Refund transaksi untuk invoice: ' . $orderEntity->invoice
                                )) {
                                    //1: pending, 2: success, 3: failed, 4: expired, 5: refund, 6: cancel
                                    $orderEntity->set('payment_status', 5);
                                    $this->Orders->save($orderEntity);

                                    $orderEntity->order_digital->set('status', 2);
                                    $this->Orders->OrderDigitals->save($orderEntity->order_digital);
                                }
                                $this->Orders->getConnection()->commit();

                                if (property_exists($subject, 'Mailer')) {
                                    $this->Mailer = $subject->Mailer;
                                    $this->Mailer
                                        ->setVar([
                                            'invoice' => $orderEntity->invoice,
                                            'customer_number' => $orderEntity->order_digital->customer_number,
                                            'product_digital_name' => $orderEntity->order_digital->digital_detail->name,
                                            'status' => 'gagal'
                                        ])
                                        ->send(
                                            $orderEntity->customer->email,
                                            "Status transaksi mobile untuk invoice: " . $orderEntity->invoice,
                                            'transaction_mobile'
                                        );

                                }

                                if (property_exists($subject, 'Notification')) {
                                    $this->Notification = $subject->Notification;
                                    $this->Notification->create(
                                        $orderEntity->customer_id,
                                        '1',
                                        'Transaksi digital gagal',
                                        vsprintf('Pembelian %s untuk %s dengan invoice %s gagal', [
                                            $orderEntity->order_digital->customer_number,
                                            $orderEntity->order_digital->digital_detail->name,
                                            $orderEntity->invoice
                                        ]),
                                        'Orders',
                                        $orderEntity->id,
                                        1,
                                        $this->Notification->getImageDigitalProductPath(),
                                        '/user/history/detail/' . $orderEntity->invoice
                                    );


                                    $this->Notification->triggerCount(
                                        $orderEntity->customer_id,
                                        $orderEntity->customer->reffcode
                                    );
                                }

                            }
                            break;
                    }
                }

            } else if ($orderEntity && count($orderEntity->order_details) > 0) {



                //transaction for mutation point
                $this->Orders->getConnection()->begin();
                foreach($orderEntity->order_details as $detail) {
                    if (is_array($detail->order_detail_products)) {
                        foreach($detail->order_detail_products as $detail_product) {
                            if ($detail_product instanceof \App\Model\Entity\OrderDetailProduct) {
                                if ($detail_product->bonus_point > 0) {
                                    $this->Orders
                                        ->Customers
                                        ->CustomerMutationPoints
                                        ->saving(
                                            $orderEntity->customer_id,
                                            3,
                                            intval($detail_product->bonus_point),
                                            'bonus point belanja'
                                        );
                                }

                            }

                        }
                    }
                }
                $this->Orders->getConnection()->commit();

                if (property_exists($subject, 'OrderShippingDetails')) {
                    $this->OrderShippingDetails = $subject->OrderShippingDetails;
                } else {
                    $this->OrderShippingDetails = (new TableLocator())->get('OrderShippingDetails');
                }

                /*
                if (property_exists($subject, 'ProductRatings')) {
                    $this->ProductRatings = $subject->ProductRatings;
                } else {
                    $this->ProductRatings = (new TableLocator())->get('ProductRatings');
                }*/


                foreach($orderEntity->order_details as $vals){
                    foreach($vals->order_shipping_details as $value){
                        $query = $this->OrderShippingDetails->query();
                        $query->update()
                            ->set(['status' => 2])
                            ->where([
                                'order_detail_id' => $value['order_detail_id'],
                                'status' => 1,
                            ])
                            ->execute();
                    }

                    ///trigger insert row product ratting
                    foreach($vals->order_detail_products as $value){
                        //check before save
                        /*
                        $check = $this->ProductRatings->find()
                            ->where([
                                'order_id' => $orderEntity->get('id'),
                                'product_id' => $value->product_id,
                            ])->first();
                        if(empty($check)){
                            $saveRatting = $this->ProductRatings->newEntity([
                                'order_id' => $orderEntity->get('id'),
                                'product_id' => $value->product_id,
                                'customer_id' => $orderEntity->get('customer_id'),
                                'rating' => 0,
                                'status' => 0,
                            ]);
                            $this->ProductRatings->save($saveRatting);
                        }*/
                    }

                    $this->Mailer
                        ->setVar([
                            'orderEntity' => $orderEntity,
                            'transactionEntity' => $transactionEntity
                        ])
                        ->send(
                            $orderEntity->customer->email,
                            vsprintf('checkout pesanan berhasil untuk pembayaran %s', [
                                $orderEntity->invoice
                            ]),
                            'success_payment'
                        );

                }

                //sent notification
                if ($this->Notification->create(
                    $orderEntity->customer_id,
                    '1',
                    'Pembayaran telah dikonfirmasi',
                    vsprintf('Konfirmasi pembayaran sebesar %s dengan nomor invoice %s telah diterima, silahkan menunggu kiriman barang', [
                        Number::format($orderEntity->total),
                        $orderEntity->invoice
                    ]),
                    'Orders',
                    $orderEntity->id,
                    1,
                    $this->Notification->getImageConfirmationPath(),
                    '/user/history/detail/' . $orderEntity->invoice
                )) {

                    $this->Notification->triggerCount(
                        $orderEntity->customer_id,
                        $orderEntity->customer->reffcode
                    );
                }




            }

            //create room chat related invoice
            if (property_exists($subject, 'ChatKit')) {
                $this->ChatKit = $subject->ChatKit;
                try {
                    $this->ChatKit->createUser(
                        $orderEntity->customer->username,
                        $orderEntity->customer->first_name . ' ' . $orderEntity->customer->last_name
                    );
                } catch(\Exception $e) {
                    Log::warning($e->getMessage(), ['scope' => ['chatkit']]);
                }


                switch ($orderEntity->order_type) {
                    case '1':
                        foreach($orderEntity->order_details as $val) {

                            $products = [];
                            $images = [];
                            foreach($val->order_detail_products as $detail_product) {
                                array_push($products, $detail_product->product_id);
                            }

                            if (count($products) > 0) {
                                $productImages = $this
                                    ->Orders
                                    ->OrderDetails
                                    ->OrderDetailProducts
                                    ->Products
                                    ->ProductImages->find()
                                    ->where([
                                        'product_id IN' => $products
                                    ])
                                    ->group('ProductImages.product_id');

                                $mainSite = rtrim(Configure::read('mainSite'), '/') . '/images/100x100/';
                                if (!$productImages->isEmpty()) {
                                    /**
                                     * @var \App\Model\Entity\ProductImage[] $productImages
                                     */
                                    foreach($productImages as $product_image) {
                                        array_push($images, $mainSite . $product_image->name);
                                    }
                                }
                            }


                            try {
                                $this->ChatKit->getInstance()->createRoom([
                                    'creator_id' => $orderEntity->customer->username,
                                    'name' => $orderEntity->invoice . '-' . $val->id,
                                    'user_ids' => ['administrator', $orderEntity->customer->username],
                                    'private' => true,
                                    'custom_data' => [
                                        'order_id' => $orderEntity->id,
                                        'order_detail_id' => $val->id,
                                        'shipping_cost' => $val->shipping_cost,
                                        'total' => $val->total,
                                        'images' => $images,
                                        'products' => $products,
                                        'order_type' => $orderEntity->order_type
                                    ]
                                ]);
                            } catch(\Exception $e) {
                                Log::warning($e->getMessage(), ['scope' => ['chatkit']]);
                            }
                        }
                    break;
                    case '2':
                        if ($transaction_digital_process_status) {
                            try {
                                $this->ChatKit->getInstance()->createRoom([
                                    'creator_id' => $orderEntity->customer->username,
                                    'name' => $orderEntity->invoice . '-' . $orderEntity->order_digital->id,
                                    'user_ids' => ['administrator', $orderEntity->customer->username],
                                    'private' => true,
                                    'custom_data' => [
                                        'order_id' => $orderEntity->id,
                                        'order_digital_id' => $orderEntity->order_digital->id
                                    ]
                                ]);
                            } catch(\Exception $e) {
                                Log::warning($e->getMessage(), ['scope' => ['chatkit']]);
                            }
                        }
                    break;
                }



            }



        }







    }

    public function expired(Event $event)
    {
        // Code to update statistics
    }
}