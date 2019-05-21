<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerBalancesTable $CustomerBalances
 * @property \App\Model\Table\CustomerMutationAmountsTable $CustomerMutationAmounts
 * @property \App\Model\Table\CustomerMutationPointsTable $CustomerMutationPoints
 * @property \App\Controller\Component\GenerationsTreeComponent $GenerationsTree
 * @property \App\Model\Table\CustomerAddresesTable $CustomerAddreses
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RegistersController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerBalances');
        $this->loadModel('CustomerMutationAmounts');
        $this->loadModel('CustomerMutationPoints');
        $this->loadModel('CustomerAddreses');
        $this->loadComponent('GenerationsTree');
        $this->loadComponent('Sms');
    }

    private function reffcode($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            try {
                $pieces []= $keyspace[random_int(0, $max)];
            } catch(\Exception $e) {}
        }
        return implode('', $pieces);
    }



    public function index()
    {

        $this->SendAuth->register('register', $this->request->getData('phone'));
        $validator = new Validator();
        $validator
            ->requirePresence('auth_code')
            ->notEmpty('auth_code', 'This field is required')
            ->add('auth_code', 'is_valid', [
                'rule' => function($value) {
                    return $this->SendAuth->isValid($value);
                },
                'message' => 'Auth code not valid'
            ]);

//        if($this->request->getData('reffcode')){
//            $validator
//                ->requirePresence('reffcode')
//                ->notEmpty('reffcode', 'reffcode is required')
//                ->add('auth_code', 'is_valid', [
//                    'rule' => function($value) {
//                        return $this->SendAuth->isValid($value);
//                    },
//                    'message' => 'Auth code not valid'
//                ]);
//        }

        // display error custom on controller
        $errors = $validator->errors($this->request->getData());
        if (empty($errors)) {
            $success = false;
            $register = $this->Customers->newEntity();
            $register = $this->Customers->patchEntity($register, $this->request->getData(),['fields' => ['first_name', 'last_name', 'email','username','password','cpassword','phone']]);
            $register->set('reffcode', strtoupper($this->reffcode('10')));
            $register->set('customer_group_id', 1);
            $register->set('customer_status_id', 1);
            $register->set('is_verified', $register->phone ? 1 : 0);
            $register->set('avatar', 'avatar.jpg');
            $register->set('platforrm', 'Android');
            $register->set('activation', \Cake\Utility\Text::uuid());

            $save = $this->Customers->save($register);
            if($save){



                $balanceEntity = $this->CustomerBalances->newEntity([
                    'customer_id' => $save->get('id'),
                    'balance' => 0,
                    'point' => 0
                ]);
                if ($this->CustomerBalances->save($balanceEntity)) {

                    $this->SendAuth->setUsed();
                    $this->Mailer
                        ->setVar([
                            'code' => $save->get('activation'),
                            'name' => $save->get('username'),
                            'email' => $save->get('email'),
                        ])
                        ->send(
                            $save->get('id'),
                            'Verifikasi Alamat Email Kamu Di Zolaku',
                            'verification'
                        );

                }
            }else{
                $this->setResponse($this->response->withStatus(406, 'Failed to registers'));
                //display error on models
                $error = $register->getErrors();
            }
        }else {
            $this->setResponse($this->response->withStatus(406, 'Failed to registers'));
            $error = $errors;
        }

        $this->set(compact('error'));
    }

    public function sendcode(){
        $this->SendAuth->register('register', $this->request->getData('phone'));
        $code = $this->SendAuth->generates();
        if($code){
            $customerCheck = $this->Customers->find()
                ->where(['phone' => $this->request->getData('phone')])
                ->first();
            if($customerCheck){

            }else{
                $text = 'Demi keamanan, mohon TIDAK MEMBERIKAN kode kepada siapapun TERMASUK TIM ZOLAKU. Kode berlaku 15 mnt : '.$code;
                $this->Sms->send($this->request->getData('phone'),$text);
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'request telah di kirim, silahkan tunggu 15 menit sampai sesi habis.'));
        }
        $this->set(compact('error'));
    }


    public function verification($code = null){
        $this->request->allowMethod('get');

        $code = $this->request->getQuery('code');
        if(!empty($code)){

            $customers = $this->Customers->find()
                ->where(['activation' => $code, 'is_verified' => 0])
                ->first();
            if($customers){

                $update = $this->Customers->get($customers->id);
                $update->is_verified = 1;
                $update->activation = null;
                if($this->Customers->save($update)){
                    $this->Mailer
                        ->setVar([
                            'code' => $customers->get('activation'),
                            'name' => $customers->get('username'),
                            'email' => $customers->get('email'),
                        ])
                        ->send(
                            $customers->get('id'),
                            'Verifikasi Di Zolaku Berhasil',
                            'actived'
                        );

                }else{
                    $this->setResponse($this->response->withStatus(406, 'Failed request verification'));
                }

            }else{
                $this->setResponse($this->response->withStatus(406, 'Wrong code'));
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'Verification code is empty'));
        }
        $this->set(compact('error'));

    }


}