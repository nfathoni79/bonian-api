<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use App\Model\Entity\ProductOptionStock;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Cache\Cache;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerResetPasswordTable $CustomerResetPassword
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ForgotPasswordController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerResetPassword');
        $this->loadModel('CustomerAuthenticates');
    }



    public function index()
    {
        $this->request->allowMethod('post');


        $validator = new Validator();

        $validator
            //->requirePresence('session_id', 'create', 'no session id')
            ->requirePresence('email', 'create', 'email require')
            //->notBlank('session_id', 'no session id')
            ->notBlank('email', 'Tidak boleh kosong')
            ->add('email', 'exists_email', [
                'rule' => function($value)  {
                    return $this->Customers->find()
                        ->where([
                            'OR' => [
                                'email' => $value,
                                'phone' => preg_replace('/^0/', '+62', $value),
                            ]
                        ])
                        ->count() > 0;
                },
                'message' => 'Email / handphone tidak terdaftar.'
            ]);

        $error = $validator->errors($this->request->getData());
        if (!$error) {
            $customerEntity = $this->Customers->find()
                ->select([
                    'id',
                    'email'
                ])
                ->where([
                    'OR' => [
                        'email' => $this->request->getData('email'),
                        'phone' => preg_replace('/^0/', '+62', $this->request->getData('email')),
                    ]
                ])
                ->first();

            $isEmail = filter_var($this->request->getData('email'), FILTER_VALIDATE_EMAIL);

            $resetPassword = $this->CustomerResetPassword->newEntity([
                'customer_id' => $customerEntity->get('id'),
                'session_id' => Security::randomString(), //$this->request->getData('session_id'),
                'request_name' => $this->request->getData('email'),
                'request_type' => $isEmail ? 1 : 2,
                'otp' => rand(111111, 999999),
                'status' => 0
            ]);

            if ($this->CustomerResetPassword->save($resetPassword)) {

                if ($isEmail) {
                    $this->Mailer
                        ->setVar([
                            'otp' => $resetPassword->otp
                        ])
                        ->send(
                            $customerEntity->get('id'),
                            'Atur ulang password anda.',
                            'forgot_password'
                        );
                } else {
                    $text = 'Zolaku, Request reset password, Kode OTP berlaku 15 mnt : '. $resetPassword->otp;
                    $this->Sms->send(preg_replace('/^\+62/i', '0', $resetPassword->request_name), $text);
                }



                $data = [
                  'request_type' => $resetPassword->request_type,
                    'session_id' => $resetPassword->get('session_id')
                ];
            }

        } else {
            $this->response = $this->response->withStatus(406);
        }

        $this->set(compact('error', 'data'));
    }


    public function otp()
    {
        $this->request->allowMethod('post');
        $validator = new Validator();



        $validator
            ->requirePresence('session_id', 'create', 'no session id')
            ->requirePresence('otp', 'create', 'otp require')
            ->notBlank('session_id', 'no session id')
            ->notBlank('otp', 'Tidak boleh kosong')
            ->add('otp', 'otp_verification', [
                'rule' => function($value, $context)  {
                    $context['data']['session_id'] = isset($context['data']['session_id']) ? $context['data']['session_id'] : null;
                    return $this->CustomerResetPassword->find()
                            ->where([
                                'otp' => $value,
                                'session_id' => $context['data']['session_id'],
                                'status' => 0
                            ])
                            ->count() > 0;
                },
                'message' => 'Kode OTP salah.'
            ]);

        $error = $validator->errors($this->request->getData());
        if (!$error) {
            $entity = $this->CustomerResetPassword->find()
                ->where([
                    'otp' => $this->request->getData('otp'),
                    'session_id' => $this->request->getData('session_id'),
                    'status' => 0
                ])->first();


            if ($entity) {
                $entity->set('status', 1);
                $this->CustomerResetPassword->save($entity);
            }
            $data = [
                'session_id' => $this->request->getData('session_id')
            ];

        } else {
            $this->response = $this->response->withStatus(406);
        }

        $this->set(compact('error', 'data'));
    }


    public function setPassword()
    {
        $this->request->allowMethod('post');



        $validator = $this->Customers->getValidator('password')
            ->requirePresence('password', 'create', 'Password tidak boleh kosong')
            ->requirePresence('repeat_password', 'create', 'Kofirmasi password tidak boleh kosong')
            ->requirePresence('session_id', 'create', 'no session id')
            ->add('session_id', 'valid', [
                'rule' => function($value)  {
                    return $this->CustomerResetPassword->find()
                            ->where([
                                'session_id' => $value,
                                'status' => 1
                            ])
                            ->count() > 0;
                },
                'message' => 'Invalid session id.'
            ]);

        $getData = $this->request->getData();

        /*foreach($getData as $key => $val) {
            if (in_array($key, ['password', 'repeat_password'])) {
                if (ctype_xdigit($val)) {
                    $getData[$key] = $this->Tools->decrypt($val);
                }
            }
        }*/



        $error = $validator->errors($getData);

        if (!$error) {
            /**
             * @var \App\Model\Entity\CustomerResetPassword $entity
             */
            $entity = $this->CustomerResetPassword->find()
                ->contain([
                    'Customers' => [
                        'fields' => [
                            'id',
                            'first_name',
                            'last_name',
                            'email',
                            'password',
                            'avatar',
                            'customer_status_id',
                            'reffcode',
                            'is_verified',
                        ]
                    ]
                ])
                ->where([
                    'session_id' => $this->request->getData('session_id'),
                    'CustomerResetPassword.status' => 1
                ])
                ->first();

            if ($entity) {
                $this->Customers->patchEntity($entity->customer, $getData, [
                    'validate' => false,
                    'fields' => [
                        'password'
                    ]
                ]);

                if (!$this->Customers->save($entity->customer)) {
                    $this->setResponse($this->response->withStatus(406, 'Failed change password'));
                    $error = $entity->customer->getErrors();
                } else {

                    $entity->set('status', 2);
                    $this->CustomerResetPassword->save($entity);

                    $data = [
                        'finish' => true
                    ];

                    //reset token to expired
                    $this->CustomerAuthenticates->query()
                        ->update()
                        ->set([
                            'expired' => (Time::now())->format('Y-m-d H:i:s')
                        ])
                        ->where([
                            'customer_id' => $entity->customer_id
                        ])
                        ->execute();


                    $this->Mailer
                        ->setVar([
                            'email' => $entity->customer->email,
                            'date' => date('d M Y H:i:s')
                        ])
                        ->send(
                            $entity->customer->get('id'),
                            'Password berhasil diubah.',
                            'success_reset_password'
                        );
                }
            }

        } else {
            $this->response = $this->response->withStatus(406);
        }

        $this->set(compact('error', 'data'));

    }


}