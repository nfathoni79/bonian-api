<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/02/2019
 * Time: 12:45
 */

namespace App\Controller\V1\Web;


use Cake\Http\Client;
use Cake\Http\Client\FormData;
use Cake\Log\Log;
use Cake\Utility\Security;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use App\Lib\SocialConnect\AccessToken;

/**
 * Class LoginController
 * @package App\Controller\V1
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerAuthenticatesTable CustomerAuthenticates
 * @property \App\Model\Table\IpLocationsTable IpLocations
 */
class OauthController extends AppController
{

    protected $config = [];

    /**
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerAuthenticates');
        $this->loadModel('IpLocations');
        $this->config = Configure::read('Oauth');
    }

    /**
     * @return \SocialConnect\Auth\Service
     */
    protected function setService()
    {
        $bid = $this->request->getHeader('bid');
        if(count($bid) > 0) {
            $bid = $bid[0];
        } else {
            $bid = null;
        }

        $httpClient = new \SocialConnect\Common\Http\Client\Curl();
        /**
         * By default collection factory is null, in this case Auth\Service will create
         * a new instance of \SocialConnect\Auth\CollectionFactory
         * you can use custom or register another providers by CollectionFactory instance
         */
        $collectionFactory = null;


        $service = new \SocialConnect\Auth\Service(
            $httpClient,
            new \App\Lib\SocialConnect\File($bid),
            $this->config,
            $collectionFactory
        );
        return $service;
    }

    /**
     *
     */
    public function index()
    {
        $userAgent = $this->request->getHeader('user-agent');
        if(count($userAgent) > 0) {
            $userAgent = $userAgent[0];
        } else {
            $userAgent = null;
        }

        $callback = $this->request->getHeader('callback');
        if(count($callback) > 0) {
            $this->config['redirectUri'] = $callback[0];
        }

        //debug($this->config);exit;

        $service = $this->setService();

        if ($provider = $this->request->getQuery('provider')) {
            try {
                $provider = $service->getProvider($provider);
                $redirect = $provider->makeAuthUrl();
            } catch(\Exception $e) {
                $this->setResponse($this->response->withStatus(400, $e->getMessage()));
            }

        }

        $this->set(compact('redirect'));
    }

    public function register($providerName)
    {
        $callback = $this->request->getHeader('callback');
        if(count($callback) > 0) {
            $this->config['redirectUri'] = $callback[0];
        }

        $service = $this->setService();

        if (!$service->getFactory()->has($providerName)) {
            $this->setResponse($this->response->withStatus(400, 'invalid provider'));
        } else {

            try {
                $provider = $service->getProvider($providerName);

                if ($access_token = $this->request->getQuery('access_token')) {
                    $oauthToken = new AccessToken($providerName, $access_token);

                } else {
                    $oauthToken = $provider->getAccessTokenByRequestParameters($this->request->getQueryParams());
                }


                $oauth = [
                    'token' => $oauthToken->getToken(),
                    'expires' => $oauthToken->getExpires(),
                    'uid' => $oauthToken->getUserId()
                ];

                $profile = $provider->getIdentity($oauthToken);

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

                if (!$ip) {
                    $ip = env('REMOTE_ADDR');
                }



                if ($profile && $profile instanceof \SocialConnect\Common\Entity\User) {
                    //do with register status
                    //check exists
                    $user = $this->Customers->find()
                        ->select([
                            'id',
                            'first_name',
                            'last_name',
                            'email',
                            'username',
                            'phone',
                            'password',
                            'avatar',
                            'customer_status_id',
                            'reffcode',
                            'is_verified',
                        ])
                        ->where([
                            'email' => $profile->email
                        ])->first();

                    if (!$user) {
                        //do register user
                        $register = $this->Customers->newEntity(null, ['validate' => false]);


                        $register->set('email', $profile->email);

                        if ($first_name = $profile->firstname) {
                            $register->set('first_name', $first_name);
                        }

                        if ($last_name = $profile->lastname) {
                            $register->set('last_name', $last_name);
                        }


                        $register->set('reffcode', strtoupper($this->Tools->reffcode('10')));
                        $register->set('customer_group_id', 1);
                        $register->set('customer_status_id', 1);
                        $register->set('is_verified', 0);
                        $register->set('is_email_verified', $profile->emailVerified);
                        $register->set('avatar', 'avatar.jpg');
                        $register->set('platforrm', $this->request->getQuery('platform', 'Web'));

                        $save = $this->Customers->save($register);
                        if($save){
                            $balanceEntity = $this->Customers->CustomerBalances->newEntity([
                                'customer_id' => $save->get('id'),
                                'balance' => 0,
                                'point' => 0
                            ]);
                            if ($this->Customers->CustomerBalances->save($balanceEntity)) {

                            }

                            try {
                                $tmp = tempnam(sys_get_temp_dir(), 'avatar');
                                file_put_contents($tmp, file_get_contents($profile->pictureURL));
                                $http = new Client();
                                $data = new FormData();
                                $data->add('customer_id', $save->get('id'));
                                $file = $data->addFile('avatar', fopen($tmp, 'r'));
                                $file->filename(basename($profile->pictureURL));
                                $http->post(Configure::read('postImage').'/avatar', (string)$data, ['headers' => ['Content-Type' => $data->contentType()]]);
                                @unlink($tmp);
                            } catch(\Exception $e) {
                                Log::error($e->getMessage());
                            }

                            //get again
                            $user = $this->Customers->find()
                                ->select([
                                    'id',
                                    'first_name',
                                    'last_name',
                                    'email',
                                    'username',
                                    'phone',
                                    'password',
                                    'avatar',
                                    'customer_status_id',
                                    'reffcode',
                                    'is_verified',
                                ])
                                ->where([
                                    'email' => $profile->email
                                ])->first();

                        }
                    }

                    if (!$bid) {
                        $bid = Security::hash($user->get('username') . $userAgent . $ip, 'sha256', true); //($username . $userAgent . $ip);
                    }


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
                            'expired' => (Time::now())->addMonth(6)->format('Y-m-d H:i:s')
                        ]);
                    }

                    if($this->CustomerAuthenticates->save($find)) {
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


            } catch (\Exception $e) {
                $this->setResponse($this->response->withStatus(400, $e->getMessage()));
            }
        }

        $this->set(compact('data', 'error'));
    }



    public function cb($providerName)
    {
		$callback = $this->request->getHeader('callback');
        if(count($callback) > 0) {
            $this->config['redirectUri'] = $callback[0];
        }
		
        $service = $this->setService();

        if (!$service->getFactory()->has($providerName)) {
            $this->setResponse($this->response->withStatus(400, 'invalid provider'));
        } else {
			
            try {
                $provider = $service->getProvider($providerName);

                if ($access_token = $this->request->getQuery('access_token')) {
                    $oauthToken = new AccessToken($providerName, $access_token);

                } else {
                    $oauthToken = $provider->getAccessTokenByRequestParameters($this->request->getQueryParams());
                }

                

                $oauth = [
                  'token' => $oauthToken->getToken(),
                    'expires' => $oauthToken->getExpires(),
                    'uid' => $oauthToken->getUserId()
                ];

                //debug($accessToken->getUserId());
                //debug($accessToken->getExpires());

                $profile = $provider->getIdentity($oauthToken);

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

                if (!$ip) {
                    $ip = env('REMOTE_ADDR');
                }





                if ($profile && $profile instanceof \SocialConnect\Common\Entity\User) {
                    $user = $this->Customers->find()
                        ->select([
                            'id',
                            'first_name',
                            'last_name',
                            'email',
                            'username',
                            'phone',
                            'password',
                            'avatar',
                            'customer_status_id',
                            'reffcode',
                            'is_verified',
                        ])
                        ->where([
                            'email' => $profile->email
                        ])->first();



                    if ($user) {

                        if (!$bid) {
                            $bid = Security::hash($user->get('username') . $userAgent . $ip, 'sha256', true); //($username . $userAgent . $ip);
                        }

                        $message = '';
                        switch($user->get('customer_status_id')) {
                            case '2':
                                $message = 'Status email anda di blok, silahkan hubungi customer service';
                                $this->setResponse($this->response->withStatus(406, $message));
                                break;
                            case '3':
                                $message = 'Status email anda pending, silahkan konfirmasi email';
                                $this->setResponse($this->response->withStatus(406, $message));
                                break;
                        }

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
                                'expired' => (Time::now())->addMonth(6)->format('Y-m-d H:i:s')
                            ]);
                        }

                        if ($this->CustomerAuthenticates->save($find)) {
                            $this->Tools->initialTableIpLocation()
                                ->saveIpLocation($ip);
                        }

                        $data = [
                            'id' => $user->get('id'),
                            'email' => $user->get('email'),
                            'username' => $user->get('username'),
                            'phone' => $user->get('phone'),
                            'first_name' => $user->get('first_name'),
                            'last_name' => $user->get('last_name'),
                            'avatar' => $user->get('avatar'),
                            'customer_status_id' => $user->get('customer_status_id'),
                            'reffcode' => $user->get('reffcode'),
                            'is_verified' => $user->get('is_verified'),
                            'token' => $token
                        ];
                    } else {
                        $this->setResponse($this->response->withStatus(406, 'Gagal login'));
                    }
                }


            } catch (\Exception $e) {
                $this->setResponse($this->response->withStatus(400, $e->getMessage()));
            }
        }

        $this->set(compact('data', 'oauth', 'error'));
    }

}