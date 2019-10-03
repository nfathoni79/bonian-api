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

use Cake\Auth\DefaultPasswordHasher;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Cake\I18n\FrozenTime;
use  Cake\ORM\ResultSet;
use Cake\Utility\Security;
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
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
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
        $this->loadModel('CustomerAuthenticates');
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

    public function revokeToken()
    {
        $this->request->allowMethod('get');
        $token = $this->Authenticate->getToken();
        if ($token) {
            $tokenEntity = $this->CustomerAuthenticates->find()
                ->where([
                    'token' => $token
                ])->first();

            if ($tokenEntity) {
                //revoke token to expired
                $tokenEntity->set('expired', Time::now()->format('Y-m-d H:i:s'));
                $this->CustomerAuthenticates->save($tokenEntity);
            } else {
                $this->setResponse($this->response->withStatus(406, 'token not found'));
            }


        } else {
            $this->setResponse($this->response->withStatus(406, 'token not found'));
        }
    }


    public function changePassword()
    {
        $this->request->allowMethod('post');

        $passwordEntity = $this->Customers->find()
            ->select([
                'id',
                'password',
                'username'
            ])
            ->where([
                'id' => $this->Authenticate->getId()
            ])
            ->first();

        $validator = $this->Customers->getValidator('password');

        $validator->requirePresence('password')
            ->requirePresence('repeat_password');

        $error = $validator->errors($this->request->getData());

        if (!$error) {
            if ($passwordEntity->get('password')) {
                $validator->requirePresence('current_password');
                $validator->notBlank('current_password', 'Kolom ini harus diisi')
                    ->add('current_password', 'check_password', [
                        'rule' => function($value) use ($passwordEntity) {
                            return (new DefaultPasswordHasher())->check($value, $passwordEntity->get('password'));
                        },
                        'message' => 'Password lama anda tidak valid'
                    ]);
            }


            $this->Customers->patchEntity($passwordEntity, $this->request->getData(), [
                'validate' => 'password',
                'fields' => [
                    'password'
                ]
            ]);

            if (!$this->Customers->save($passwordEntity)) {
                $this->setResponse($this->response->withStatus(406, 'Failed change password'));
                $error = $passwordEntity->getErrors();
            } else {

                //reset token to expired
                $this->CustomerAuthenticates->query()
                    ->update()
                    ->set([
                        'expired' => (Time::now())->format('Y-m-d H:i:s')
                    ])
                    ->where([
                        'customer_id' => $this->Authenticate->getId()
                    ])
                    ->execute();


                //generate new token
                if (!$this->request->getData('dont_request_token')) {
                    $bid = $this->request->getHeader('bid');
                    if(count($bid) > 0) {
                        $bid = $bid[0];
                    } else {
                        $bid = null;
                    }

                    $userAgent = $this->request->getHeader('user-agent');
                    if(count($userAgent) > 0) {
                        $userAgent = $userAgent[0];
                    } else {
                        $userAgent = null;
                    }

                    $ip = $this->request->getHeader('ip');

                    if(count($ip) > 0) {
                        $ip = $ip[0];
                    } else {
                        $ip = null;
                    }

                    if (!$ip) {
                        $ip = $this->request->clientIp();
                    }

                    if (!$bid) {
                        $bid = Security::hash($passwordEntity->get('username') . $userAgent . $ip, 'sha256', true); //($username . $userAgent . $ip);
                    }
                    $browserEntity = $this->CustomerAuthenticates->Browsers->find()
                        ->where([
                            'bid' => $bid
                        ])
                        ->first();

                    if (!$browserEntity) {
                        $browserEntity = $this->CustomerAuthenticates->Browsers->newEntity([
                            'bid' => $bid,
                            'user_agent' => $userAgent
                        ]);
                        $this->CustomerAuthenticates->Browsers->save($browserEntity);
                    }

                    $key = Security::randomString();
                    $token = base64_encode(Security::encrypt(json_encode([
                        'id' => $passwordEntity->get('id'),
                        'email' => $passwordEntity->get('email'),
                        'token' => $key
                    ]), Configure::read('Encrypt.salt')));

                    $find = $this->CustomerAuthenticates->newEntity([
                        'customer_id' => $passwordEntity->get('id'),
                        'token' => $key,
                        'browser_id' => $browserEntity->get('id'),
                        'expired' => (Time::now())->addMonth(6)->format('Y-m-d H:i:s')
                    ]);



                    $data = [];

                    if ($this->CustomerAuthenticates->save($find)) {
                        $data['token'] = $token;
                    }
                }



            }
        } else {
            $this->setResponse($this->response->withStatus(406, 'Please fill the require input'));
        }



        $this->set(compact('error', 'data'));

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
                $data = $result->data;

                try {
                    $userEntity = $this->Customers->find()
                        ->select([
                            'username',
                            'avatar'
                        ])
                        ->where([
                            'id' => $this->Authenticate->getId()
                        ])
                        ->first();

                    if ($userEntity && $userEntity->get('username')) {
                        $user = $this->ChatKit->getInstance()->getUser([ 'id' => $userEntity->get('username') ]);
                        if (isset($user['body'])) {
                            //$avatar_url = $user['body']['avatar_url'];
                            $avatar_url = rtrim(Configure::read('mainSite'), '/') . '/files/Customers/avatar/thumbnail-' . $data;

                            try {
                                $this->ChatKit->getInstance()->updateUser([
                                    'id' => $userEntity->get('username'),
                                    'avatar_url' => $avatar_url
                                ]);
                            }catch(\Exception $e) {

                            }

                        }
                    }


                } catch(\Exception $e) {

                }


            }else{
                $this->setResponse($this->response->withStatus(406, 'Unable to update data profile'));
            }
        }
        $this->set(compact('error', 'data', 'user'));
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
                //$row->phone = $row->phone;
                $row->phone_masked = $this->Tools->maskPhone($row->phone);


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