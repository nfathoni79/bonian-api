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
class ChangeEmailController extends AppController
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

            Cache::write($data['session_id'], ['step' => 2, 'entity' => $passwordEntity], 'change_email');


        } else {
            $this->response = $this->response->withStatus(406);
        }


        $this->set(compact('error', 'data'));
    }

    public function getStep($step) {
        $this->request->allowMethod('post');
        if ($session_id = $this->request->getData('session_id')) {
            if ($cache = Cache::read($session_id, 'change_email')) {
                if ($cache['step'] != $step) {
                    return $this->response->withStatus(404,'failed step');
                }
            } else {
                $this->response = $this->response->withStatus(404, 'no cache file');
            }
        } else {
            $this->response = $this->response->withStatus(404, 'No session id');
        }
        $this->set(compact('cache'));
    }


    public function setEmail()
    {
        $data = ['email' => null];


        if ($this->request->is('post')) {
            if ($session_id = $this->request->getData('session_id')) {
                if ($cache = Cache::read($session_id, 'change_email')) {

                    //valid step
                    if ($cache['step'] != 2) {
                        return $this->response->withStatus(404,'failed step');
                    }

                    $validator = new Validator();
                    $validator
                        ->email('email', true, 'Email tidak sah')
                        ->add('email', 'is_unique', [
                            'rule' => function($value) {
                                return $this->Customers->find()
                                        ->where([
                                            'email' => $value,
                                            //'id !=' => $this->Authenticate->getId()
                                        ])
                                        ->count() == 0;
                            },
                            'message' => 'Email sudah terdaftar.'
                        ]);

                    $error = $validator->errors($this->request->getData());

                    if (!$error) {

                        if (isset($cache['entity'])) {
                            /**
                             * @var \App\Model\Entity\Customer $customerEntity
                             */
                            $customerEntity = $cache['entity'];
                            if ($customerEntity instanceof \App\Model\Entity\Customer) {
                                $customerEntity->set('email', $this->request->getData('email'));
                                //$customerEntity->set('is_email_verified', 0);
                                if ($this->Customers->save($customerEntity)) {
                                    $data = [
                                        'session_id' => $this->request->getData('session_id', rand(1000, 9999)),
                                        'step' => 3,
                                    ];



                                }
                            }
                        }

                        $cache['step'] = $data['step'];
                        $cache['email'] = $this->request->getData('email');

                        Cache::write($data['session_id'], $cache, 'change_email');

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


    public function verification()
    {
        if ($this->request->is('post')) {
            if ($session_id = $this->request->getData('session_id')) {
                if ($cache = Cache::read($session_id, 'change_email')) {

                }
            } else {
                $this->response = $this->response->withStatus(404, 'No session id');
            }
        }
    }

}
