<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\I18n\Number;


/**
 * Static content controller
 *  @property \App\Model\Table\OrdersTable $Orders
 *  @property \App\Model\Table\TransactionsTable $Transactions
 * This controller will render views from Template/Ipn/
 * 
 */
class IpnController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $config = Configure::read('Midtrans');
        $this->merchant_id = $config['merchantid'];
        $this->client_key = $config['clientKey'];
        $this->server_key = $config['serverKey'];

        $this->loadModel('Orders');
        $this->loadModel('Transactions');
    }



	public function index() {
		$this->disableAutoRender();

		$responseText = '';


		//hook for testing only
        /*$this->getEventManager()->dispatch(new Event('Controller.Ipn.success', $this, [
            'transactionEntity' => $this->Transactions->newEntity([
                'id' => 34,
                'order_id' => 191,
                'status_code' => 200
            ])
        ]));*/



        if ($this->request->is('post')) {
            $content = $this->request->getBody()->getContents();
            Log::notice($content, ['scope' => ['midtrans']]);

            $content = json_decode($content, true);

            if ($content && isset($content['status_code'])) {

                switch($content['payment_type']) {
                    case 'bank_transfer':
                        if (isset($content['va_numbers'])) {
                            foreach($content['va_numbers'] as $va) {
                                $content['va_number'] = $va['va_number'];
                                $content['bank'] = $va['bank'];
                            }
                        }
                        break;
                }

                /**
                 * @var \App\Model\Entity\Order $orderEntity
                 */
                $orderEntity = $this->Orders->find()
                    ->contain([
                        'Customers'
                    ])
                    ->where([
                        'invoice' => $content['order_id']
                    ])
                    ->first();

                if ($orderEntity) {

                    $transactionEntity = $this->Transactions->find()
                        ->where([
                            'order_id' => $orderEntity->get('id'),
                            'transaction_id' => $content['transaction_id']
                        ])
                        ->first();

                    if ($transactionEntity) {
                        $this->Transactions->patchEntity($transactionEntity, $content);

                        //patch again with order_id relations to table orders
                        $this->Transactions->patchEntity($transactionEntity, [
                            'order_id' => $orderEntity->get('id'),
                            'is_called' => true
                        ]);

                        $is_update = ($transactionEntity->isDirty('transaction_status') &&
                            $transactionEntity->isDirty('status_code')) ||
                            ($transactionEntity->get('payment_type') == 'credit_card' && $transactionEntity->isDirty('is_called'));

                        if ($this->Transactions->save($transactionEntity)) {

                            if ($is_update) {
                                //$content['status_code'] == 200
                                if ($content['status_code'] == 200) {
                                    $orderEntity->set('payment_status', 2);
                                    //sent event to listener
                                    $this->getEventManager()->dispatch(new Event('Controller.Ipn.success', $this, [
                                        'transactionEntity' => $transactionEntity
                                    ]));

                                    //sent notification
                                    if ($this->Notification->create(
                                        $orderEntity->customer_id,
                                        '1',
                                        'Pembayaran telah dikonfirmasi',
                                        vsprintf('Konfirmasi pembayaran sebesar %s', [Number::format($orderEntity->total)]),
                                        'Orders',
                                        $orderEntity->id
                                    )) {
                                        $pusher = $this->Pusher->Pusher();
                                        $total = $this->Notification->getTable()->find()
                                            ->where([
                                                'customer_id' => $orderEntity->customer_id,
                                                'is_read' => 0
                                            ])->count();
                                        
                                        try {
                                            $pusher->trigger(
                                                'private-notification',
                                                'my-event-' . $orderEntity->customer->reffcode,
                                                ['total' => $total]
                                            );
                                        } catch (\Exception $e) {

                                        }

                                    }





                                } else if (strtolower($content['transaction_status']) == 'expire') {
                                    $orderEntity->set('payment_status', 4); // 4: expired
                                    $this->getEventManager()->dispatch(new Event('Controller.Ipn.expired', $this, [
                                        'transactionEntity' => $transactionEntity
                                    ]));

                                    //sent notification
                                    $this->Notification->create(
                                        $orderEntity->customer_id,
                                        '1',
                                        'Pembayaran telah melebihi batas ketentuan',
                                        vsprintf('Pesanan sebesar %s telah dibatalkan', [Number::format($orderEntity->total)]),
                                        'Orders',
                                        $orderEntity->id
                                    );
                                }
                                $responseText = "OK";
                            }

                            $this->Orders->save($orderEntity);

                        }
                    }

                }

            }

		}

        return $this->response->withStringBody($responseText);
	}
	
	
	 

}
