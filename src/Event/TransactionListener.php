<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 06/05/2019
 * Time: 9:51
 */

namespace App\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\Event;
use App\Controller\Component\SepulsaComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Log\Log;

/**
 * Class TransactionListener
 * @package App\Event
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\TransactionsTable $Transactions
 *
 * @property \App\Controller\Component\MailerComponent $Mailer
 * @property \App\Controller\Component\SepulsaComponent $Sepulsa
 * @property \App\Controller\Component\NotificationComponent $Notification
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
        /**
         * @var \App\Model\Entity\Transaction $transactionEntity
         */
        $transactionEntity = $event->getData('transactionEntity');

        $subject = $event->getSubject();
        if (property_exists($subject, 'Orders')) {
            $this->Orders = $subject->Orders;
            /**
             * @var \App\Model\Entity\Order $orderEntity
             */
            $orderEntity = $this->Orders->find()
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
                ->first();

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
                                }
                                $this->Orders->getConnection()->commit();

                                if (property_exists($subject, 'Mailer')) {
                                    $this->Mailer = $subject->Mailer;
                                    $this->Mailer
                                        ->setVar([
                                            'invoice' => $orderEntity->invoice,
                                            'customer_number' => $orderEntity->order_digital->customer_number,
                                            'product_digital_name' => $orderEntity->order_digital->digital_detail->name,
                                            'status' => $e->getMessage()
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
            }
        }







    }

    public function expired(Event $event)
    {
        // Code to update statistics
    }
}