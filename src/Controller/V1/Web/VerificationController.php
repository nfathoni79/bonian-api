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
use Cake\Validation\Validator;

/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class VerificationController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
    }

    /**
     * list all address
     */
    public function email()
    {
        $customers = $this->Customers->find()
            ->select(['id', 'email','is_email_verified','activation','username'])
            ->where(['email' => $this->request->getData('email'), 'is_email_verified' => 0])
            ->first();
        if($customers){

            $this->Mailer
                ->setVar([
                    'code' => $customers->get('activation'),
                    'name' => $customers->get('username'),
                    'email' => $customers->get('email'),
                    'activation_url' => $this->request->getData('activation_url', '')
                ])
                ->send(
                    $customers->get('id'),
                    'Verifikasi Alamat Email Kamu Di Zolaku',
                    'verification'
                );

        }else{
            $this->setResponse($this->response->withStatus(406, 'Email telah diferivikasi'));
        }
    }

    public function setPhone()
    {
        $this->request->allowMethod('post');
        $customerEntity = $this->Customers->find()
            ->where([
                'Customers.id' => $this->Authenticate->getId()
            ])
            ->first();

        if ($customerEntity->get('is_verified') != 1) {

            $phone = $this->request->getData('phone');
            $phone = preg_replace('/^\+/i', '', $phone);
            $phone = preg_replace('/^\0/i', '', $phone);

            $phone_with_cc = '+62' . $phone;

            $this->SendAuth->register('update-phone', $phone_with_cc);

            $validator = new Validator();
            $validator->requirePresence('phone', 'create', 'Nomor handphone tidak boleh kosong')
                ->notBlank('phone', 'Nomor Handphone tidak boleh kosong')
                ->minLength('phone', 9, 'Handphone minimal 9 digit')
                ->maxLength('phone', 11, 'Handphone maksimal 11 digit')
                ->add('phone', 'is_valid', [
                    'rule' => function($value) {
                        return $this->SendAuth->exists() == 0;
                    },
                    'message' => 'Kode OTP telah dikirim, silahkan menunggu 15 menit.'
                ]);

            $error = $validator->errors(['phone' => $phone]);
            if (empty($error)) {
                $phone = $phone_with_cc;

                $this->Customers->patchEntity($customerEntity, ['phone' => $phone], [
                    'fields' => ['phone']
                ]);

                if ($this->Customers->save($customerEntity)) {
                    $data = [
                      'phone' => $phone
                    ];
                    $code = $this->SendAuth->generates();
                    $text = 'Demi keamanan, mohon TIDAK MEMBERIKAN kode kepada siapapun TERMASUK TIM ZOLAKU. Kode berlaku 15 mnt : '.$code;
                    //$this->Sms->send('0' . preg_replace('/^\+62/i', '', $phone), $text);
                } else {
                    $error = $customerEntity->getErrors();
                    $this->setResponse($this->response->withStatus(406, 'Gagal setting handphone'));
                }

            } else {
                $this->setResponse($this->response->withStatus(406, 'Gagal setting handphone'));
            }

        } else {
            $this->setResponse($this->response->withStatus(406, 'handphone sudah di verifikasi'));
        }

        $this->set(compact('error', 'data'));
    }

    public function phoneOtp()
    {
        $this->request->allowMethod('post');
        $customerEntity = $this->Customers->find()
            ->where([
                'Customers.id' => $this->Authenticate->getId()
            ])
            ->first();

        if ($customerEntity && $customerEntity->get('phone')) {
            $this->SendAuth->register('update-phone', $customerEntity->get('phone'));
        } else {
            $this->setResponse($this->response->withStatus(406, 'handphone belum di set'));
        }

        $validator = new Validator();
        $validator->notBlank('otp', 'Kode OTP tidak boleh kosong')
            ->add('otp', 'is_valid', [
                'rule' => function($value) {
                    return $this->SendAuth->isValid($value);
                },
                'message' => 'Kode OTP tidak valid.'
            ]);

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {
            $customerEntity->set('is_verified', 1);
            if ($this->Customers->save($customerEntity)) {
                $this->SendAuth->setUsed();
            } else {
                $error = $customerEntity->getErrors();
                $this->setResponse($this->response->withStatus(406, 'Gagal setting handphone / otp'));
            }
        } else {
            $this->setResponse($this->response->withStatus(406, 'Gagal setting handphone / otp'));
        }

        $this->set(compact('error', 'data'));
    }

    public function setUsername()
    {
        $this->request->allowMethod('post');

        $customerEntity = $this->Customers->find()
            ->where([
                'Customers.id' => $this->Authenticate->getId()
            ])
            ->first();

        if ($customerEntity && !$customerEntity->get('username')) {
            $this->Customers->patchEntity($customerEntity, $this->request->getData(), [
                'fields' => ['username']
            ]);

            if ($this->Customers->save($customerEntity)) {
                $data = [
                    'username' => $customerEntity->get('username')
                ];
            } else {
                $error = $customerEntity->getErrors();
                $this->setResponse($this->response->withStatus(406, 'Gagal setting username'));
            }
        }

        $this->set(compact('error', 'data'));
    }

}
