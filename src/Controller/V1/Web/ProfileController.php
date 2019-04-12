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
use Cake\Utility\Hash;
use Cake\I18n\FrozenTime;
use  Cake\ORM\ResultSet;
use Cake\Validation\Validator;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Http\Client\FormData;
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomerWishesTable $CustomerWishes
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerNotificationsTable $CustomerNotifications
 * @property \App\Model\Table\CustomerMutationPointsTable $CustomerMutationPoints
 * @property \App\Model\Table\CustomerMutationAmountsTable $CustomerMutationAmounts
 * @property \App\Controller\Component\GenerationsTreeComponent $GenerationsTree
 * @property \App\Controller\Component\SmsComponentComponent $Sms
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ProfileController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerNotifications');
        $this->loadModel('CustomerMutationPoints');
        $this->loadModel('CustomerMutationAmounts');
        $this->loadComponent('GenerationsTree');
        $this->loadComponent('Sms');
    }

    public function edit(){
        $customerId = $this->Authenticate->getId();
        $this->request->allowMethod('post');

        $validator = new Validator();
        $validator
            ->requirePresence('name')
            ->notBlank('name', 'Nama harus diisi');
        $validator
            ->requirePresence('dob')
            ->date('dob')
            ->notBlank('dob', 'Tanggal lahir harus diisi');
        $validator
            ->requirePresence('gender')
            ->inList('gender',['M','F'])
            ->notBlank('gender', 'Jenis kelamin harus diisi');
        $error = $validator->errors($this->request->getData());
        if (empty($error)) {

            $entity = $this->Customers->get($customerId);
            $explodeName = explode(' ', $this->request->getData('name'));
            $entity->set('first_name',$explodeName[0]);
            $entity->set('last_name',@$explodeName[1]);
            $entity->set('dob',$this->request->getData('dob'));
            $entity->set('gender',$this->request->getData('gender'));

            if($this->Customers->save($entity)){

            }else{
                $this->setResponse($this->response->withStatus(406, 'Unable to update data profile'));
            }

        }else {
            $this->setResponse($this->response->withStatus(406, 'Failed to edit profile'));

        }

        $this->set(compact('error'));


    }


    public function uploadImage(){

        $this->request->allowMethod('post');
        $validator = new Validator();
        $validator
            ->requirePresence('avatar')
            ->add('avatar', [
                'validExtension' => [
                    'rule' => ['extension',['jpg','png','jpeg']], // default  ['gif', 'jpeg', 'png', 'jpg']
                    'message' => __('These files extension are allowed: .jpg, .png')
                ]
            ]);

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {
            $http = new Client();
            $data = new FormData();
            $data->add('customer_id', $this->Authenticate->getId());
            $file = $data->addFile('avatar', fopen($this->request->getData('avatar.tmp_name'), 'r'));
            $file->filename($this->request->getData('avatar.name'));

            $response = $http->post(Configure::read('postImage').'/avatar', (string)$data,['headers' => ['Content-Type' => $data->contentType()]]);
            $result = json_decode($response->getBody()->getContents());
            if($result->is_success){

            }else{
                $this->setResponse($this->response->withStatus(406, 'Unable to update data profile'));
            }
        }
        $this->set(compact('error'));
    }


    public function index(){
        $this->request->allowMethod('get');
        $data = $this->Customers->find()
            ->select([
                'id',
                'reffcode',
                'email',
                'username',
                'phone',
                'dob',
                'gender',
                'first_name',
                'last_name',
                'customer_group_id',
                'is_verified',
            ])
            ->contain([
                'CustomerGroups' => [
                    'fields' => [
                        'name'
                    ]
                ],
                'CustomerBalances' => [
                    'fields' => [
                        'customer_id',
                        'balance',
                        'point',
                    ]
                ],
                'ReferralCustomer'
            ])
            ->where(['Customers.id' => $this->Authenticate->getId()])
            ->enableAutoFields(true)
            ->map(function (\App\Model\Entity\Customer $row) {
                $row->name = $row->first_name .' '. $row->last_name;
                $row->account_type = $row->customer_group->name;
                $row->is_verified_text = ($row->is_verified != '1') ? 'Not Verified' : 'Verified';
                $row->wallet_balance = $row->customer_balances[0]->balance;
                $row->point_balance =  $row->customer_balances[0]->point;
                $row->gender_name = ($row->gender == 'm') ? 'Male' : 'Female';
                $row->dob = $row->dob instanceof \Cake\I18n\FrozenDate  ? $row->dob->format('Y-m-d') : $row->dob;


                unset($row->customer_balances);
                unset($row->customer_group);
                unset($row->customer_group_id);
                unset($row->id);
                return $row;
            })
            ->first();

        $this->set(compact('data'));

    }


    public function notifications(){

        $this->request->allowMethod('get');

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);
        $notifications = $this->CustomerNotifications->find()
            ->select([
                'kategori' => 'CustomerNotificationTypes.name',
                'CustomerNotifications.content',
                'CustomerNotifications.status',
                'CustomerNotifications.created'
            ])
            ->contain(['CustomerNotificationTypes'])
            ->where([
                'CustomerNotifications.customer_id' => $this->Authenticate->getId()
            ]);


        $notifications
            ->orderDesc('CustomerNotifications.id');

        $data = $this->paginate($notifications);

        $this->set(compact('data'));
    }

    public function points(){

        $this->request->allowMethod('get');

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);
        $notifications = $this->CustomerMutationPoints->find()
            ->select([
                'kategori' => 'CustomerMutationPointTypes.name',
                'tipe' => 'CustomerMutationPointTypes.type',
                'description' => 'CustomerMutationPoints.description',
                'amount' => 'CustomerMutationPoints.amount',
                'balance' => 'CustomerMutationPoints.balance',
                'created' => 'CustomerMutationPoints.created'
            ])
            ->contain(['CustomerMutationPointTypes'])
            ->where([
                'CustomerMutationPoints.customer_id' => $this->Authenticate->getId()
            ]);


        $notifications
            ->orderDesc('CustomerMutationPoints.id');

        $data = $this->paginate($notifications,['limit' => 300]);

        $this->set(compact('data'));
    }


    public function wallet(){

        $this->request->allowMethod('get');

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);
        $notifications = $this->CustomerMutationAmounts->find()
            ->select([
                'kategori' => 'CustomerMutationAmountTypes.name',
                'tipe' => 'CustomerMutationAmountTypes.type',
                'description' => 'CustomerMutationAmounts.description',
                'amount' => 'CustomerMutationAmounts.amount',
                'balance' => 'CustomerMutationAmounts.balance',
                'created' => 'CustomerMutationAmounts.created'
            ])
            ->contain(['CustomerMutationAmountTypes'])
            ->where([
                'CustomerMutationAmounts.customer_id' => $this->Authenticate->getId()
            ]);


        $notifications
            ->orderDesc('CustomerMutationAmounts.id')->limit(300);

        $data = $this->paginate($notifications,['limit' => 300]);

        $this->set(compact('data'));
    }


    public function addRefferal(){

        $this->request->allowMethod('post');

        $customerId = $this->Authenticate->getId();
        if($this->Customers->checkRefferal($customerId)){
            $getReffCode = $this->Customers->getRefferalCode($customerId);
            $validator = new Validator();
            $validator
                ->requirePresence('refferal')
                ->notBlank('refferal', 'Refferal code wajib di isi')
                ->notEquals('refferal', $getReffCode, 'Referal code tidak bisa di gunakan pada diri sendiri' )
                ->add('refferal', 'custom', [
                    'rule' => function ($value, $context) use($customerId) {
                        return $this->Customers->checkRefferalCode($value, $customerId) ;
                    },
                    'message' => 'Refferal tidak tersedia',
                ]);

            $error = $validator->errors($this->request->getData());
            if (empty($error)) {
                $this->GenerationsTree->save($getReffCode, $this->request->getData('refferal'));
            }else{
                $this->setResponse($this->response->withStatus(406, 'Failed to registers'));
            }
            $this->set(compact('error'));
        }else{
            $this->setResponse($this->response->withStatus(406, 'Refferal is already registered'));
        }
    }

}