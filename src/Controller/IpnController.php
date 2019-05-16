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
 *  @property \App\Model\Table\OrderShippingDetailsTable $OrderShippingDetails
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
        $this->loadModel('OrderShippingDetails');
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
                        'Customers',
                        'OrderDetails' => [
                            'OrderShippingDetails'
                        ]
                    ])
                    ->where([
                        'invoice' => $content['order_id']
                    ])
                    ->first();

                if ($orderEntity) {

                    /**
                     * @var \App\Model\Entity\Transaction $transactionEntity
                     */
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
                            $transactionEntity->isDirty('status_code') || $transactionEntity->isDirty('is_called')) ||
                            ($transactionEntity->get('payment_type') == 'credit_card' && $transactionEntity->isDirty('is_called'));

                        if ($this->Transactions->save($transactionEntity)) {

                            if ($is_update) {
                                //$content['status_code'] == 200
                                if ($content['status_code'] == 200) {
                                    $orderEntity->set('payment_status', 2);

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
                                    }

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


                                } else if (strtolower($content['transaction_status']) == 'pending' && $content['transaction_type'] == 'bank_transfer') {
                                    //sent notification

                                    if ($this->Notification->create(
                                        $orderEntity->customer_id,
                                        '1',
                                        'Menunggu pembayaran',
                                        vsprintf('Anda memiliki waktu 24 jam untuk membayar pesanan sebesar %s ke nomor virtual akun %s %s dengan nomor invoice %s', [
                                            Number::format($orderEntity->total),
                                            $transactionEntity->bank,
                                            $transactionEntity->va_number,
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
                                        vsprintf('Pesanan dengan nomor invoice %s sebesar %s telah dibatalkan', [
                                            $orderEntity->invoice,
                                            Number::format($orderEntity->total)
                                        ]),
                                        'Orders',
                                        $orderEntity->id,
                                        1,
                                        $this->Notification->getImageWarningPath(),
                                        '/user/history/detail/' . $orderEntity->invoice
                                    );
                                    $this->Notification->triggerCount(
                                        $orderEntity->customer_id,
                                        $orderEntity->customer->reffcode
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
