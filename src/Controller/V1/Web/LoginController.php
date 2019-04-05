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

        //debug($this->request->getAttribute('authorization'));exit;

        $user = $this->Customers->find()
            ->select([
                'id',
                'email',
                'password',
                'customer_status_id',
                'is_verified',
            ])
            ->where([
                'OR' => [
                    'email' => $username,
                ]
            ])->first();


        if ($user) {
            //$this->Auth->setUser($user);
            if ($user->get('customer_status_id') == '1') {
                if ((new DefaultPasswordHasher())->check($password, $user->get('password'))) {

                    $key = Security::randomString();
                    $token = base64_encode(Security::encrypt(json_encode([
                        'id' => $user->get('id'),
                        'email' => $user->get('email'),
                        'token' => $key
                    ]), Configure::read('Encrypt.salt')));

                    $find = $this->CustomerAuthenticates->find()
                        ->where([
                            'customer_id' => $user->get('id')
                        ])->first();

                    if ($find) {
                        //$find->set('token', $key);
                        //$find->set('expired', (Time::now())->addMonth($this->addMonth)->format('Y-m-d H:i:s'));
                        $token = base64_encode(Security::encrypt(json_encode([
                            'id' => $user->get('id'),
                            'email' => $user->get('email'),
                            'token' => $find->get('token')
                        ]), Configure::read('Encrypt.salt')));
                    } else {
                        $find = $this->CustomerAuthenticates->newEntity([
                            'customer_id' => $user->get('id'),
                            'token' => $key,
                            'expired' => (Time::now())->addMonth($this->addMonth)->format('Y-m-d H:i:s')
                        ]);
                    }

                    $this->CustomerAuthenticates->save($find);

                    $data = [
                        'email' => $user->get('email'),
                        'first_name' => $user->get('first_name'),
                        'last_name' => $user->get('last_name'),
                        'customer_status_id' => $user->get('customer_status_id'),
                        'token' => $token
                    ];

                    $this->set(compact('data'));
                } else {
                    $this->setResponse($this->response->withStatus(406, 'Invalid password'));
                }
            } else {
                $this->setResponse($this->response->withStatus(406, 'User is not active'));
            }



        } else {
            //Username or password is incorrect
            $this->setResponse($this->response->withStatus(406, 'Username or password is incorrect'));
        }


    }
}