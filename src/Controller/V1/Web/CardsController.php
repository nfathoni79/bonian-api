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

use App\Lib\MidTrans\CreditCardToken;
use Cake\I18n\Time;
use Cake\Validation\Validator;

/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CardsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('CustomerCards');
    }

    /**
     * list all address
     */
    public function index()
    {

        $data = $this->CustomerCards->find()
            ->select([
                'masked_card',
                'is_primary',
                'expired_at'

            ])
            ->where([
                'customer_id' => $this->Auth->user('id')
            ])
            ->map(function (\App\Model\Entity\CustomerCard $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                return $row;
            });

        $this->set(compact('data'));
    }

    /**
     * process add credit cards
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);

        $customer_id = $this->Auth->user('id');


        $credit_Card_validator = new Validator();
        $credit_Card_validator->requirePresence('number')
            ->creditCard('number');

        $credit_Card_validator->requirePresence('exp_month')
            ->notBlank('exp_month')
            ->minLength('exp_month', 2)
            ->maxLength('exp_month', 2);

        $credit_Card_validator->requirePresence('exp_year')
            ->notBlank('exp_year')
            ->minLength('exp_year', 4)
            ->maxLength('exp_year', 4);

        $credit_Card_validator->requirePresence('cvv')
            ->notBlank('cvv')
            ->minLength('cvv', 3)
            ->maxLength('cvv', 3);

        $error = $credit_Card_validator->errors($this->request->getData());
        if (empty($error)) {
            unset($error);

            //save credit card token
            $number = $this->request->getData('number');
            $exp_month = $this->request->getData('exp_month');
            $exp_year = $this->request->getData('exp_year');
            $cvv = $this->request->getData('cvv');


            //process saved token
            $token = new CreditCardToken($number, $exp_month, $exp_year);
            $response = $token->register();
            if ($response && $response['status_code'] == 200) {
                //saved_token_id masked_card
                $customer_card_entity = $this->CustomerCards->find()
                    ->where([
                        'customer_id' => $customer_id,
                        'masked_card' => $response['masked_card']
                    ])
                    ->first();

                $count_card = $this->CustomerCards->find()
                    ->where([
                        'customer_id' => $customer_id
                    ])
                    ->count();

                if (!$customer_card_entity) {
                    $customer_card_entity = $this->CustomerCards->newEntity([
                        'customer_id' => $customer_id,
                        'masked_card' => $response['masked_card'],
                        'token' => $response['saved_token_id'],
                        'is_primary' => $count_card == 0 ? 1 : 0
                    ]);

                    if(!$this->CustomerCards->save($customer_card_entity)) {
                        $this->setResponse($this->response->withStatus(406, 'Gagal menyimpan kartu kredit'));
                    }
                } else {
                    
                    $this->setResponse($this->response->withStatus(406, 'Nomor kartu kredit tersebut sudah terdaftar'));
                }

            }


        }



        $this->set(compact('error'));

    }

    /**
     * delete address given address_id
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'put']);
        if ($card_id = $this->request->getData('card_id')) {
            $cardEntity = $this->CustomerCards->find()
                ->where([
                    'customer_id' => $this->Auth->user('id'),
                    'id' => $card_id
                ])
                ->first();

            if ($cardEntity) {
                if (!$this->CustomerCards->delete($cardEntity)) {
                    $this->setResponse($this->response->withStatus(406, 'Gagal melakukan hapus kartu kredit'));
                }
            }

        }
    }




}
