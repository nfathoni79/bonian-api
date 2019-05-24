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
use Cake\Validation\Validator;
use Cake\Cache\Cache;

/**
 * Customers controller
 *
 * @property \App\Controller\Component\SmsComponent $Sms
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ChangePhoneController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadComponent('Sms');
    }

    public function index()
    {
        $this->request->allowMethod('post');

        $passwordEntity = $this->Customers->find()
            ->select([
                'id',
                'password'
            ])
            ->where([
                'id' => $this->Authenticate->getId()
            ])
            ->first();

        $validator = new Validator();

        $validator
            ->notBlank('password', 'Password tidak boleh kosong')
            ->add('password', 'verify_password', [
            'rule' => function($value) use ($passwordEntity)  {
                return (new DefaultPasswordHasher())->check($value, $passwordEntity->get('password'));
            },
            'message' => 'Password lama anda tidak valid'
        ]);

        $error = $validator->errors($this->request->getData());
        if (!$error) {
            $data = [
                'session_id' => $this->request->getData('session_id', rand(1000, 9999)),
                'step' => 2,
            ];

            Cache::write($data['session_id'], ['step' => 2, 'entity' => $passwordEntity], 'change_phone');


        } else {
            $this->response = $this->response->withStatus(406);
        }


        $this->set(compact('error', 'data'));
    }


    public function setPhone()
    {
        $data = ['phone' => null];
        $find = $this->Customers->find()
            ->select([
                'phone'
            ])
            ->where([
                'id' => $this->Authenticate->getId()
            ])
            ->first();
        if ($find) {
            $data['phone'] = $this->Tools->maskPhone($find->get('phone'));
        }

        if ($this->request->is('post')) {
            if ($session_id = $this->request->getData('session_id')) {
                if ($cache = Cache::read($session_id, 'change_phone')) {

                    //valid step
                    if ($cache['step'] != 2) {
                        return $this->response->withStatus(404,'failed step');
                    }

                    $validator = new Validator();
                    $validator
                        ->notBlank('old_phone', 'Nomor handphone lama tidak boleh kosong')
                        ->equals('old_phone', $find->get('phone'), 'Nomor handphone lama salah')
                        ->notBlank('phone', 'Nomor handphone baru tidak boleh kosong')
                        ->regex('phone', '/^(\+\d{11,13}|0\d{9,11})$/', 'Nomor handphone tidak valid')
                        ->add('phone', 'is_unique', [
                            'rule' => function($value)   {
                                $value = preg_replace('/^0/i', '+62', $value);
                                return $this->Customers->find()
                                        ->where([
                                            'phone' => $value,
                                            //'id !=' => $this->Authenticate->getId()
                                        ])
                                        ->count() == 0;
                            },
                            'message' => 'Nomor handphone sudah terdaftar.'
                        ]);

                    $error = $validator->errors($this->request->getData());

                    if (!$error) {
                        $data = [
                            'session_id' => $this->request->getData('session_id', rand(1000, 9999)),
                            'step' => 3,
                            'phone' => $data['phone']
                        ];

                        $cache['step'] = $data['step'];
                        $cache['phone'] = preg_replace('/^0/i', '+62', $this->request->getData('phone'));

                        $this->SendAuth->register('change-phone', $cache['phone']);
                        $code = $this->SendAuth->generates();

                        if (!$code) {
                            $text = 'Zolaku, Request perubahan handphone, Kode OTP berlaku 15 mnt : '. $code;
                            $this->Sms->send(preg_replace('/^\+62/i', '0', $cache['phone']), $text);
                        }

                        $cache['otp'] = $code;

                        Cache::write($data['session_id'], $cache, 'change_phone');

                    } else {

                        $this->response = $this->response->withStatus(406);
                    }

                } else {
                    $this->response = $this->response->withStatus(404, 'No session id');
                }
            }
        }


        $this->set(compact('error', 'data'));
    }

    public function getStep($step) {

    }

    public function verification()
    {
        $this->request->allowMethod('post');
        if ($session_id = $this->request->getData('session_id')) {
            if ($cache = Cache::read($session_id, 'change_phone')) {

                //valid step
                if ($cache['step'] != 3) {
                    return $this->response->withStatus(404,'failed step');
                }

                $this->SendAuth->register('change-phone', $cache['phone']);

                $validator = new Validator();
                $validator
                    ->notBlank('otp', 'kode OTP tidak boleh kosong')
                    ->add('otp', 'is_valid', [
                    'rule' => function($value) {
                        return $this->SendAuth->isValid($value);
                    },
                    'message' => 'kode OTP salah.'
                ]);

                $error = $validator->errors($this->request->getData());
                if (!$error) {
                    if (isset($cache['entity'])) {
                        /**
                         * @var \App\Model\Entity\Customer $customerEntity
                         */
                        $customerEntity = $cache['entity'];
                        if ($customerEntity instanceof \App\Model\Entity\Customer) {
                            $customerEntity->set('phone', $cache['phone']);
                            if ($this->Customers->save($customerEntity)) {
                                $data = [
                                    'session_id' => $this->request->getData('session_id', rand(1000, 9999)),
                                    'step' => 4,
                                ];
                                Cache::delete($data['session_id'], 'change_phone');
                                $this->SendAuth->setUsed();
                            }
                        }
                    }


                } else {
                    $this->response = $this->response->withStatus(406);
                }
            } else {
                $this->response = $this->response->withStatus(404, 'No session id');
            }
        }
        $this->set(compact('error', 'data'));
    }

}
