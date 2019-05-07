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
                                $this->Orders->OrderDigitals->save($orderEntity->order_digital);


                            } catch(\GuzzleHttp\Exception\ClientException $e) {
                                //debug($e->getMessage());
                                Log::info($e->getMessage(), ['scope' => ['sepulsa']]);
                            }
                            break;
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