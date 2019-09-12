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

    public function endPoint()
    {
        $this->request->allowMethod('post');
        $pusher = $this->Pusher->Pusher();
        try {
            $token = $pusher->socket_auth(
                $this->request->getData('channel_name'),
                $this->request->getData('socket_id')
            );
            $token = json_decode($token);
            $auth = $token->auth;
        } catch(\Exception $e) {
            $this->setResponse($this->response->withStatus(406, 'failed authenticate socket'));
        }


        $this->set(compact('auth'));
    }


    public function chatEndPoint()
    {
        $this->request->allowMethod('post');

        $platform = $this->request->getHeader('platform');
        if(count($platform) > 0) {
            $platform = $platform[0];
        } else {
            $platform = null;
        }

        $customerEntity = $this->Customers->find()
            ->select([
                'first_name',
                'last_name',
                'avatar',
                'username'

            ])
            ->where([
                'id' => $this->Authenticate->getId()
            ])
            ->first();

        $user_id = $this->request->getData('user_id');
        if (empty($user_id)) {
            $user_id = $this->request->getQuery('user_id');
        }

        if ($customerEntity->get('username') == $user_id) {
            try {
                $user = $this->ChatKit->getInstance()->getUser([ 'id' => $user_id ]);
            } catch(\Exception $e) {
                try {

                    $entity = [
                        'id' => $user_id,
                        'name' => $customerEntity ? $customerEntity->get('first_name') . ' ' . $customerEntity->get('last_name') : '',
                        'custom_data' => [
                            'is_admin' => false
                        ]
                    ];

                    if ($customerEntity->get('avatar')) {
                        $entity['avatar_url'] = rtrim(Configure::read('mainSite'), '/') . '/files/Customers/avatar/thumbnail-' . $customerEntity->get('avatar');
                    }

                    $user = $this->ChatKit->getInstance()->createUser($entity);
                } catch(\Exception $e) {

                }

            }

            try {
                $auth = $this->ChatKit->getInstance()->authenticate([
                    'user_id' => $user_id
                ]);
            } catch(\Exception $e) {
                $this->setResponse($this->response->withStatus(403, 'failed authenticate socket'));
            }
        } else {
            $this->setResponse($this->response->withStatus(403, 'failed authenticate socket'));
        }




        if ($platform && $platform === 'mobile') {
            return $this->response->withStatus(200)
                ->withType('application/json')
                ->withStringBody(json_encode($auth['body']));
        }

        $this->set(compact('auth', 'user'));
    }

    protected function sendNotification($ip, $userAgent, \App\Model\Entity\Customer $user)
    {
        $ua = parse_user_agent($userAgent);
        $this->Mailer
            ->setVar([
                'date' => date('Y-m-d H:i:s'),
                'device' => vsprintf('%s di %s', [$ua['browser'], $ua['platform']]),
                'ip' => $ip,
                'email' => $user->get('email'),
            ])
            ->send(
                $user->get('id'),
                'Notifikasi keamanan',
                'new_login_notification'
            );
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
        $ip = $this->request->getHeader('ip');


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

        if(count($ip) > 0) {
            $ip = $ip[0];
        } else {
            $ip = null;
        }

        if (!$ip) {
            $ip = $this->request->clientIp();
        }

        if (!$ip) {
            $ip = env('REMOTE_ADDR');
        }

        if (!$bid) {
            $bid = Security::hash($username . $userAgent . $ip, 'sha256', true); //($username . $userAgent . $ip);
        }


        $validator = new Validator();
        $validator->requirePresence('email')
            ->notBlank('email', 'Email atau nomor telepon tidak boleh kosong')
            ->add('email', 'exists', [
                'rule' => function($value) {

                    if(preg_match('/^08/',$value)){
                        $value = '+62'.substr(trim($value), 1);
                    }

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
            ->requirePresence('password', 'created', 'Tidak boleh kosong')
            ->notBlank('password', 'Tidak boleh kosong');


        $error = $validator->errors($this->request->getData());

        if (empty($error)) {
            /**
             * @var \App\Model\Entity\Customer $user
             */

            if(preg_match('/^08/',$username)){
                $username = '+62'.substr(trim($username), 1);
            }

            $user = $this->Customers->find()
                ->select([
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'username',
                    'password',
                    'avatar',
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


                /**
                 * @var \App\Model\Entity\CustomerAuthenticate $find
                 */
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
                        if ($this->CustomerAuthenticates->Browsers->save($browserEntity)) {
                            $this->sendNotification($ip, $userAgent, $user);

                        }
                    }

                    $find = $this->CustomerAuthenticates->newEntity([
                        'customer_id' => $user->get('id'),
                        'token' => $key,
                        'browser_id' => $browserEntity->get('id'),
                        'ip' => $ip,
                        'expired' => (Time::now())->addMonth($this->addMonth)->format('Y-m-d H:i:s')
                    ]);
                }

                if ($this->CustomerAuthenticates->save($find)) {
                    if ($find->isNew()) {
                        $this->sendNotification($ip, $userAgent, $user);
                    }

                    $this->Tools->initialTableIpLocation()
                        ->saveIpLocation($ip);
                }

                $data = [
                    'id' => $user->get('id'),
                    'email' => $user->get('email'),
                    'phone' => $user->get('phone'),
                    'username' => $user->get('username'),
                    'first_name' => $user->get('first_name'),
                    'last_name' => $user->get('last_name'),
                    'avatar' => $user->get('avatar'),
                    'customer_status_id' => $user->get('customer_status_id'),
                    'reffcode' => $user->get('reffcode'),
                    'is_verified' => $user->get('is_verified'),
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