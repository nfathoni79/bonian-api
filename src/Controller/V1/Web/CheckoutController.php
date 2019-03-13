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
    }

    /**
     * list all address
     */
    public function index()
    {

        $this->request->allowMethod(['post', 'put']);


        $trx = new Transaction('ord-0019-x101');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke');

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

            $token = $this->MidTrans->createToken(new Token(
                '4411 1111 1111 1118',
                '01',
                '20',
                '123'
            ), $trx->getAmount());



            if ($token['status_code'] == 200) {
                $request->setCreditCard($token['token_id'], true);

                $charge = $this->MidTrans->charge($request);
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
                                'token' => $saved_token,
                                'masked_card' => $masked_card,
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
