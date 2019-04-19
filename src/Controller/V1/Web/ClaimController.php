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
use Cake\Validation\Validator;
/**
 * Customers controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\CustomerBalancesTable $CustomerBalances
 * @property \App\Model\Table\CustomerVouchersTable $CustomerVouchers
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
 * @property \App\Model\Table\CustomerMutationPointsTable $CustomerMutationPoints
 * @property \App\Model\Table\IpLocationsTable $IpLocations
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ClaimController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Vouchers');
        $this->loadModel('CustomerAuthenticates');
        $this->loadModel('CustomerMutationPoints');
        $this->loadModel('CustomerBalances');
        $this->loadModel('CustomerVouchers');
    }

    public function index(){

        $this->request->allowMethod('post');

        $auth = $this->CustomerAuthenticates->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'token' => $this->Authenticate->getToken()
            ])
            ->first();

        if ($auth) {
            $getUserBalance = $this->CustomerBalances->find()->where(['CustomerBalances.customer_id' => $this->Authenticate->getId()])->first();
            if($getUserBalance){
                $voucher = $this->request->getData('voucher');
                $point = $getUserBalance->get('point'); // 0
                $findVoucher = $this->Vouchers->find()
                    ->where(['code_voucher' => $voucher, 'type' => 1])
                    ->first();
                if($findVoucher){
                    $stock = $findVoucher->get('stock');
                    $syarat = $findVoucher->get('point'); // syarat point
                    $newData = $this->request->withData('point', $point);

                    if($stock > 0){
                        $validator = new Validator();
                        $validator->requirePresence('point')
                            ->notBlank('point', 'Kode voucher dibutuhkan')
                            ->greaterThanOrEqual('point',$syarat,'Syarat point tidak cukup');
                        $validator->requirePresence('voucher')
                            ->notBlank('voucher', 'Kode voucher dibutuhkan')
                            ->add('voucher', 'exists', [
                                'rule' => function($value) {
                                    return $this->Vouchers->find()
                                            ->where([
                                                'code_voucher' => $value,
                                                'type' => 1,
                                                'stock > ' => 0,
                                            ])->count() > 0;
                                },
                                'message' => 'Code voucher tidak terdaftar'
                            ]);
                        $error = $validator->errors($newData->getData());
                        if(empty($error)){
                            /* SYARAT POINT CUKUP MAKA DEBIT POINT */
                            if($this->CustomerMutationPoints->saving($this->Authenticate->getId(),'5', ($syarat * -1), 'Redeem voucher '.$voucher)){

                                /* Update Stock */
                                $saveStock = clone $findVoucher;
                                $saveStock->set('stock', $saveStock->get('stock') - 1);
                                if($this->Vouchers->save($saveStock)){
                                    $newEntityCustVoucher = $this->CustomerVouchers->newEntity();
                                    $setEntity = [
                                        'customer_id' => $this->Authenticate->getId(),
                                        'voucher_id' => $findVoucher->get('id'),
                                        'status ' => 1,
                                        'expired' => (Time::now())->addDays(+30)->format('Y-m-d H:i:s')
                                    ];
                                    $this->CustomerVouchers->patchEntity($newEntityCustVoucher,$setEntity);
                                    $this->CustomerVouchers->save($newEntityCustVoucher);

                                }
                            } else{
                                $this->setResponse($this->response->withStatus(406, 'Terjadi kesalahan, silahkan coba lagi.'));
                            }
                        }
                    }else{
                        $this->setResponse($this->response->withStatus(406, 'Stok tidak tersedia'));
                    }
                }else{
                    $this->setResponse($this->response->withStatus(406, 'Code voucher tidak ditemukan'));
                }
            }else{
                $this->setResponse($this->response->withStatus(406, 'Unknown balance'));
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'Invalid or empty token'));
        }
        $this->set(compact( 'error'));
    }

    public function iclaim(){

        $this->request->allowMethod('post');

        $auth = $this->CustomerAuthenticates->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'token' => $this->Authenticate->getToken()
            ])
            ->first();

        if ($auth) {
            $voucher = $this->request->getData('voucher');
            $findVoucher = $this->Vouchers->find()
                ->where([
                    'code_voucher' => $voucher,
                    'stock > ' => 0,
                    'status' => 1,
                    'type' => 3,
                ])
                ->first();
            if($findVoucher){

                $saveStock = clone $findVoucher;
                $saveStock->set('stock', $saveStock->get('stock') - 1);
                $saveStock->set('status', 2);
                if($this->Vouchers->save($saveStock)){

                    $newEntityCustVoucher = $this->CustomerVouchers->newEntity();
                    $setEntity = [
                        'customer_id' => $this->Authenticate->getId(),
                        'voucher_id' => $findVoucher->get('id'),
                        'status ' => 1,
                        'expired' => (Time::now())->addDays(+30)->format('Y-m-d H:i:s')
                    ];
                    $this->CustomerVouchers->patchEntity($newEntityCustVoucher,$setEntity);
                    $this->CustomerVouchers->save($newEntityCustVoucher);
                }
            }else{
                $this->setResponse($this->response->withStatus(406, 'Maaf, kode ini tidak sah. Mohon coba kembali.'));
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'Invalid or empty token'));
        }
        $this->set(compact( 'error'));
    }

}