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
                    ]
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

                            }
                            break;
                    }
                }

            } else if ($orderEntity && count($orderEntity->order_details) > 0) {
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
            }
        }







    }

    public function expired(Event $event)
    {
        // Code to update statistics
    }
}