<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;


/**
 * Sepulsa callback url controller
 *  @property \App\Model\Table\OrdersTable $Orders
 *  @property \App\Model\Table\TransactionsTable $Transactions
 * This controller will render views from Template/Ipn/
 * 
 */
class SepulsaController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Orders');
        $this->loadModel('Transactions');
    }


    protected function sendMail($email, $subject = 'Transaksi pulsa berhasil')
    {
        $this->Mailer
            ->setVar([
                'code' => $save->get('activation'),
                'name' => $save->get('username'),
                'email' => $save->get('email'),
            ])
            ->send(
                $email,
                $subject,
                'transaction_mobile'
            );
    }


    /**
     * sepulsa callback URL
     */
	public function index()
    {
		$this->disableAutoRender();
        //Log::info(json_encode($this->request->getData()), ['scope' => ['sepulsa']]);
        Log::notice($this->request->getBody()->getContents(), ['scope' => ['sepulsa']]);


        /*
         * {
            "transaction_id": "359743",
            "type": "mobile",
            "created": "1557307537",
            "changed": 1557307541,
            "customer_number": "081234000011",
            "order_id": "1905080936B653",
            "amount": "0",
            "status": "failed",
            "price": "49000",
            "response_code": "99",
            "serial_number": null,
            "product_id": "9"
            }
         */
        $raw_response = $this->request->getBody()->getContents();
        $json = json_decode($raw_response, true);
        if ($json && isset($json['order_id'])) {

            /**
             * @var \App\Model\Entity\Order $orderEntity
             */

            $orderEntity = $this->Orders->find()
                ->where([
                    'invoice' => $json['order_id']
                ])
                ->contain([
                    'OrderDigitals' => [
                      'DigitalDetails'
                    ],
                    'Customers'
                ])
                ->first();

            if ($orderEntity) {
                //debug($orderEntity);exit;
                if ($orderEntity->order_digital instanceof \App\Model\Entity\OrderDigital) {
                    $this->Orders->getConnection()->begin();
                    switch($json['status']) {
                        case 'success':
                            //processing bonus point
                            if ($orderEntity->order_digital->bonus_point > 0 && $orderEntity->order_digital->status == 99) {
                                $point_status = $this->Orders
                                    ->Customers
                                    ->CustomerMutationPoints
                                    ->saving(
                                        $orderEntity->get('customer_id'),
                                        3,
                                        intval($orderEntity->order_digital->bonus_point),
                                        'bonus point pembelian pulsa'
                                    );
                                Log::notice("set bonus point: $point_status", ['scope' => ['sepulsa']]);
                            }
                            $orderEntity->order_digital->set('status', 1);
                        break;
                        case 'failed':
                            $orderEntity->order_digital->set('status', 2);
                        break;
                    }


                    $orderEntity->order_digital->set('raw_response', $raw_response);
                    $this->Orders->OrderDigitals->save($orderEntity->order_digital);
                    $this->Orders->getConnection()->commit();

                    $this->Mailer
                        ->setVar([
                            'invoice' => $orderEntity->invoice,
                            'customer_number' => $orderEntity->order_digital->customer_number,
                            'product_digital_name' => $orderEntity->order_digital->digital_detail->name,
                            'status' => $json['status']
                        ])
                        ->send(
                            $orderEntity->customer->email,
                            "Status transaksi mobile untuk invoice: " . $orderEntity->invoice,
                            'transaction_mobile'
                        );
                }
            }

        }



	}
	

}
