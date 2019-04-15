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
use Cake\I18n\FrozenTime;
use Cake\Validation\Validator;

/**
 * Networks controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\GenerationsTable $Generations
 * @property \App\Model\Table\CustomerBalancesTable $CustomerBalances
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class LeaderboardsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('GenerationsTree');
        $this->loadModel('Customers');
        $this->loadModel('CustomerBalances');
        $this->loadModel('Generations');
    }

    /**
     * list all address
     */
    public function index()
    {
        $this->request->allowMethod('get');

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);

        $user_id = $this->Authenticate->getId();
        $generations = $this->Generations->find('all')
            ->contain([
                'Refferals'
            ]);
        $generations->select([
            'Generations.refferal_id',
            'username' => 'Refferals.username',
            'reffcode' => 'Refferals.reffcode',
            'last_active' => 'Refferals.modified',
            'count' => $generations->func()->count('Generations.refferal_id')
        ])
            ->where(['Generations.level' => 1])
            ->group('Generations.refferal_id')
            ->limit(100);
        $generations->orderDesc('count');
        $data = $generations;

        $this->set(compact('data'));

    }

    public function checkExist($reff){
        /* check refferal already registered or not */
        $find = $this->Customers->find()
            ->where(['reffcode' => $reff])
            ->first();
        if($find){
            return true;
        }else{
            return false;
        }
    }

    public function follow(){

        $validator = new Validator();
        $validator->requirePresence('reffcode')
            ->notBlank('reffcode','Refferal code di butuhkan')
            ->add('reffcode', 'checkExist', [
                'rule' => function($value) {
                    return $this->checkExist($value);
                },
                'message' => 'Referal tidak di temukan'
            ]);

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {
            $find = $this->Customers->find()
                ->where([
                    'id'=> $this->Authenticate->getId(),
                    'refferal_customer_id' => 0
                ])
                ->first();
            if($find){
                $reffcode = $this->request->getData('reffcode');
                $sponsor = $find->get('reffcode');

                $findReff = $this->Customers->find()
                    ->where([
                        'reffcode'=> $reffcode
                    ])
                    ->first();
                if($findReff){
                    if($reffcode != $sponsor){
                        $this->GenerationsTree->save( $sponsor, $reffcode);
                    }else{
                        $this->setResponse($this->response->withStatus(404, 'Anda tidak bisa memfolow diri sendiri'));
                    }
                }else{
                    $this->setResponse($this->response->withStatus(404, 'Refferal tidak ditemukan'));
                }
            }else {
                $this->setResponse($this->response->withStatus(404, 'Refferal sudah terdaftar'));
            }
        }
        $this->set(compact('error'));
    }

}