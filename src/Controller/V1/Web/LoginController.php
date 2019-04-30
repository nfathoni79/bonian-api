<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/02/2019
 * Time: 12:45
 */

namespace App\Controller\V1\Web;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Validation\Validator;

/**
 * Class LoginController
 * @package App\Controller\V1
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerAuthenticatesTable CustomerAuthenticates
 */
class LoginController extends AppController
{

    protected $addMonth = 6;

    /**
     * initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerAuthenticates');
    }

    /**
     * index login
     */
    public function index()
    {
        $this->request->allowMethod('post');

        //$user = $this->Auth->identify();

        $username = $this->request->getData('email');
        $password = $this->request->getData('password');
        $bid = $this->request->getHeader('bid');
        $userAgent = $this->request->getHeader('user-agent');


        if(count($bid) > 0) {
            $bid = $bid[0];
        } else {
            $bid = null;
        }

        if(count($userAgent) > 0) {
            $userAgent = $userAgent[0];
        } else {
            $userAgent = null;
        }


        $validator = new Validator();
        $validator->requirePresence('email')
            ->notBlank('email', 'Email atau nomor telepon tidak boleh kosong')
            ->add('email', 'exists', [
                'rule' => function($value) {
                    return $this->Customers->find()
                        ->where([
                            'OR' => [
                                'email' => $value,
                                'phone' => $value,
                            ]
                        ])->count() > 0;
                },
                'message' => 'Email atau nomor telepon tidak terdaftar'
            ])
            ->notBlank('password', 'Tidak boleh kosong');


        $error = $validator->errors($this->request->getData());

        if (empty($error)) {
            $user = $this->Customers->find()
                ->select([
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'customer_status_id',
                    'reffcode',
                    'is_verified',
                ])
                ->where([
                    'OR' => [
                        'email' => $username,
                        'phone' => $username,
                    ]
                ])->first();

            $message = '';
            switch($user->get('customer_status_id')) {
                case '2':
                    $message = 'Status email anda di blok, silahkan hubungi customer service';
                break;
                case '3':
                    $message = 'Status email anda pending, silahkan konfirmasi email';
                break;
            }

            $validator->add('email', 'status', [
                'rule' => function($value) use ($user) {
                    return in_array($user->get('customer_status_id'), [1]);
                },
                'message' => $message
            ]);



            $validator->add('password', 'valid_password', [
                'rule' => function($password) use ($user) {
                    return (new DefaultPasswordHasher())->check($password, $user->get('password'));
                },
                'message' => 'Password anda salah'
            ]);

            $error = $validator->errors($this->request->getData());

            if (empty($error)) {
                $key = Security::randomString();
                $token = base64_encode(Security::encrypt(json_encode([
                    'id' => $user->get('id'),
                    'email' => $user->get('email'),
                    'token' => $key
                ]), Configure::read('Encrypt.salt')));



                $find = $this->CustomerAuthenticates->find()
                    ->contain([
                        'Browsers'
                    ])
                    ->where([
                        'customer_id' => $user->get('id')
                    ]);

                if ($bid) {
                    $find->where([
                        'Browsers.bid' => $bid
                    ]);
                }

                $find->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                    return $exp->gte('expired', (Time::now())->format('Y-m-d H:i:s'));
                });

                $find = $find->first();

                if ($find) {
                    $token = base64_encode(Security::encrypt(json_encode([
                        'id' => $user->get('id'),
                        'email' => $user->get('email'),
                        'token' => $find->get('token')
                    ]), Configure::read('Encrypt.salt')));

                } else {
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

                    $find = $this->CustomerAuthenticates->newEntity([
                        'customer_id' => $user->get('id'),
                        'token' => $key,
                        'browser_id' => $browserEntity->get('id'),
                        'expired' => (Time::now())->addMonth($this->addMonth)->format('Y-m-d H:i:s')
                    ]);
                }

                $this->CustomerAuthenticates->save($find);

                $data = [
                    'email' => $user->get('email'),
                    'first_name' => $user->get('first_name'),
                    'last_name' => $user->get('last_name'),
                    'customer_status_id' => $user->get('customer_status_id'),
                    'reffcode' => $user->get('reffcode'),
                    'token' => $token
                ];
            }
        }

        if ($error) {
            $this->setResponse($this->response->withStatus(406, 'Gagal login'));
        }

        $this->set(compact('data', 'error'));
    }
}