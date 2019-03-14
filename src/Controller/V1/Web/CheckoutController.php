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
use App\Lib\MidTrans\PaymentRequest;
use Cake\I18n\Time;
use App\Lib\MidTrans\Token;
use App\Lib\MidTrans\Request;
use App\Lib\MidTrans\Transaction;
use Cake\Utility\Security;
use Cake\Core\Configure;
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CheckoutController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCards');

        $this->Auth->allow(['index']);
    }


    public function index()
    {
        /*$cards = $this->CustomerCards->find()
            ->where([
                'customer_id' => $this->Auth->user('id'),
                'is_primary' => 1
            ])
            ->first();

        debug($cards->get('token'));exit;*/

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


        debug($request);

        $charge = $this->MidTrans->charge($request);
        debug($charge);

        exit;

        $ccToken = new \App\Lib\MidTrans\CreditCardToken();
        $getToken = $ccToken->setCvv(123)
            ->setToken('441111lSmrlWhaoZtyTjOAscGBrc1118')
            ->setSecure(true)
            ->request(5000);

        if ($getToken->status_code == 200) {
            if ($getToken->redirect_url) {
                return $this->redirect($getToken->redirect_url);
            }

            $payment_type = 'credit_card';






        }
    }

    /**
     * payment process
     */
    public function payment()
    {

        $this->request->allowMethod(['post', 'put']);

		
	
        $trx = new Transaction('ord-0021-x10160');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke 2');

        try {

            $request = new Request('credit_card');
            $request->addTransaction($trx);

            $request->setCustomer(
                'iwaninfo@gmail.com',
                'Ridwan',
                'Rumi',
                '08112823746'
            )
                ->setBillingAddress()
                ->setShippingFromBilling();

            $token = $this->MidTrans->createToken((new Token(
                '5211 1111 1111 1117',
                '01',
                '20',
                '123'
            ))->setSecure(true), $trx->getAmount());


			//$token['status_code'] = 200;
			//$token['token_id'] = '441111-1118-d9c7689a-82eb-469f-a797-cd0aa13edf2e';

            if ($token['status_code'] == 200) {

                if (isset($token['redirect_url'])) {
                    return $this->redirect($token['redirect_url']);
                }


                $request->setCreditCard($token['token_id'], true);

                $charge = $this->MidTrans->charge($request);
				
				//$charge = json_decode('{"status_code":"200","status_message":"Success, Credit Card transaction is successful","transaction_id":"d9c7689a-82eb-469f-a797-cd0aa13edf2e","order_id":"ord-0019-x100","gross_amount":"5000.00","currency":"IDR","payment_type":"credit_card","transaction_time":"2019-03-13 19:40:47","transaction_status":"capture","fraud_status":"accept","approval_code":"1552480848068","masked_card":"441111-1118","bank":"cimb","card_type":"credit","saved_token_id":"441111lSmrlWhaoZtyTjOAscGBrc1118","saved_token_id_expired_at":"2020-01-31 07:00:00","channel_response_code":"00","channel_response_message":"Approved"}', true);
				
                if (isset($charge['status_code']) && $charge['status_code'] == 200) {
                    if ($request->isCreditCard() && $request->isSavedToken()) {
                        //saved token



                        $saved_token = $charge['saved_token_id'];
                        $saved_token_id_expired_at = $charge['saved_token_id_expired_at'];
                        $masked_card = $charge['masked_card'];

                        $cardEntity = $this->CustomerCards->find()
                            ->where([
                                'customer_id' => $this->Auth->user('id'),
                                'masked_card' => $masked_card
                            ])
                            ->first();

                        $count_card = $this->CustomerCards->find()
                            ->where([
                                'customer_id' => $this->Auth->user('id')
                            ])
                            ->count();



                        if (empty($cardEntity)) {

                            $cardEntity = $this->CustomerCards->newEntity([
                                'customer_id' => $this->Auth->user('id'),
                                'is_primary' => $count_card > 0 ? 0 : 1,
                                'masked_card' => $masked_card,
								'token' => $saved_token,
                                'expired_at' => $saved_token_id_expired_at
                            ]);
							
							
                            $this->CustomerCards->save($cardEntity);
							
                        }

                    }
                } else {
                    $this->setResponse($this->response->withStatus(406, 'failed to request payment'));
                }

            }



        } catch(\Exception $e) {

        }

        $this->set(compact('data'));
    }

}
