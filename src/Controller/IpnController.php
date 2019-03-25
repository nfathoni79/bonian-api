<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;


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
        $config = Configure::read('Midtrans');
        $this->merchant_id = $config['merchantid'];
        $this->client_key = $config['clientKey'];
        $this->server_key = $config['serverKey'];

        $this->loadModel('Orders');
        $this->loadModel('Transactions');
    }



	public function index() {
		$this->disableAutoRender();
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

                $orderEntity = $this->Orders->find()
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
                    } else {
                        $transactionEntity = $this->Transactions->newEntity($content);
                    }

                    //patch again with order_id relations to table orders
                    $this->Transactions->patchEntity($transactionEntity, [
                        'order_id' => $orderEntity->get('id')
                    ]);

                    if ($this->Transactions->save($transactionEntity)) {
                        //$content['status_code'] == 200
                        if ($content['status_code'] == 200) {
                            $orderEntity->set('payment_status', 2);
                            $this->Orders->save($orderEntity);
                        }
                    }
                }

            }

		}
	}
	
	
	 

}
