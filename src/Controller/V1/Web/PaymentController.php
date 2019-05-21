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

use App\Lib\MidTrans\CreditCardToken;
use App\Lib\MidTrans\Payment\BcaKlikPay;
use App\Lib\MidTrans\Payment\BcaVirtualAccount;
use App\Lib\MidTrans\Payment\BniVirtualAccount;
use App\Lib\MidTrans\Payment\CreditCard;
use App\Lib\MidTrans\Payment\Gopay;
use App\Lib\MidTrans\Payment\MandiriBillPayment;
use App\Lib\MidTrans\Payment\MandiriClickPay;
use App\Lib\MidTrans\Payment\PermataVirtualAccount;
use App\Lib\MidTrans\Request;
use App\Lib\MidTrans\Transaction;

use Cake\Utility\Hash;

use Cake\I18n\Time;
use App\Lib\MidTrans\Token;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Cake\Cache\Cache;


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerDigitalInquiryTable $CustomerDigitalInquiry
 * @property \App\Model\Table\DigitalDetailsTable $DigitalDetails
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @property \App\Model\Table\OrdersTable $Orders
 *
 * @property \App\Controller\Component\SepulsaComponent $Sepulsa
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PaymentController extends AppController
{

    /**
     * available status = 1
     * @var array
     */
    protected $customerDetailStatuses = [1];
    protected $cacheKey = null;
    protected $isCreateToken = false;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerDigitalInquiry');
        $this->loadModel('DigitalDetails');
        $this->loadModel('Transactions');
        $this->loadModel('CustomerCards');
        $this->loadModel('Orders');


        $this->loadComponent('Sepulsa');

    }

    public function createToken()
    {
        $this->request->allowMethod('post');

        $validator = new Validator();
        $amount = 0;
        $validator->requirePresence('type');

        $type = $this->request->getData('type');
        switch($type) {
            case 'pulsa':
                $validator->requirePresence('inquiry_id')
                    ->add('inquiry_id', 'check_inquiry', [
                        'rule' => function($value)  {
                            return $this->CustomerDigitalInquiry->find()
                                    ->where([
                                        'customer_id' => $this->Authenticate->getId(),
                                        'id' => $value,
                                        'status' => 0
                                    ])
                                    ->count() > 0;
                        },
                        'message' => 'Invalid inquiry id'
                    ]);

                //check is inquiry_id
                if ($inquiry_id = $this->request->getData('inquiry_id')) {
                    $inquiryEntity = $this->CustomerDigitalInquiry->find()
                        ->where([
                            'customer_id' => $this->Authenticate->getId(),
                            'id' => $inquiry_id,
                            'status' => 0
                        ])
                        ->first();
                    if ($inquiryEntity) {
                        if ($code = $inquiryEntity->get('code')) {
                            $digitalDetailEntity = $this->DigitalDetails->find()
                                ->where([
                                    'code' => $code
                                ])
                                ->contain([
                                    'Digitals'
                                ])
                                ->first();
                            if ($digitalDetailEntity) {
                                $amount = $digitalDetailEntity->get('price');
                            }

                        }

                    }

                }

                break;
        }


        $error = $validator->errors($this->request->getData());

        if (!$error) {

            if ($amount > 0) {
                $customer_id = $this->Authenticate->getId();
                $customer_card_entity = $this->CustomerCards->find()
                    ->where([
                        'customer_id' => $customer_id,
                        'id' => $this->request->getData('card_id')
                    ])
                    ->first();

                if ($customer_card_entity) {
                    $credit_card_token = new CreditCardToken();
                    $token = $credit_card_token->setToken($customer_card_entity->get('token'))
                        ->setCvv($this->request->getData('cvv'))
                        ->setSecure(true)
                        ->request($amount);
                }
            } else {
                $this->setResponse($this->response->withStatus(406, 'Invalid amount'));
            }


        } else {
            $this->setResponse($this->response->withStatus(404, 'Failed to create token'));
        }


        $this->set(compact('token', 'amount'));
    }

    public function gopayStatus()
    {
        $this->request->allowMethod('post');
        if ($transaction_id = $this->request->getData('transaction_id')) {
            $transactionEntity = $this->Transactions->find()
                ->where([
                    'Transactions.transaction_id' => $transaction_id,
                    'Transactions.payment_type' => 'gopay'
                ])
                ->first();

            if ($transactionEntity) {
                $raw_response = $transactionEntity->get('raw_response');
                if ($raw_response) {
                    $response = json_decode($raw_response, true);
                    if (isset($response['actions'])) {
                        foreach($response['actions'] as $val) {
                            if ($val['name'] == 'get-status') {
                                try {
                                    $r = $this->MidTrans->makeRequest()
                                        ->get($val['url'])
                                        ->getBody()
                                        ->getContents();
                                    $data = json_decode($r, true);
                                } catch(\GuzzleHttp\Exception\ClientException $e) {}


                                break;
                            }
                        }
                    }
                }

            } else {
                $this->setResponse($this->response->withStatus(404, 'Transaction not found'));
            }
        }

        $this->set(compact('data'));
    }

    public function process()
    {

        $this->request->allowMethod('post');

        $getData = $this->request->getData();

        $customer_id = $this->Authenticate->getId();
        $payment_method = $this->request->getData('payment_method');
        $gross_total = 0;
        $total = 0;

        $getData['customer_id'] = $customer_id;

        $validator = new Validator();
        $validator->requirePresence('type');

        $validator->add('customer_id', 'verify_only', [
            'rule' => function($value) {
                return $this->Customers->find()
                        ->where([
                            'Customers.id' => $value,
                            'Customers.is_verified' => 1,
                        ])
                        ->count() == 1;
            },
            'message' => 'Maaf akun anda belum terverifikasi, silahkan verifikasi akun terlebih dahulu'
        ]);

        $invoice = strtoupper(date('ymdHs') . Security::randomString(4));
        $trx = new Transaction($invoice);

        switch($this->request->getData('type')) {
            case 'pulsa':
                $validator->requirePresence('inquiry_id')
                    ->add('inquiry_id', 'check_inquiry', [
                        'rule' => function($value)  {
                            return $this->CustomerDigitalInquiry->find()
                                    ->where([
                                        'customer_id' => $this->Authenticate->getId(),
                                        'id' => $value,
                                        'status' => 0
                                    ])
                                    ->count() > 0;
                        },
                        'message' => 'Invalid inquiry id'
                    ]);


            break;
        }

        $validator->requirePresence('payment_method')
            ->inList('payment_method', [
                'credit_card',
                'mandiri_billpayment',
                'bca_va',
                'permata_va',
                'bni_va',
                'bca_klikpay',
                'mandiri_clickpay',
                'gopay'
            ]);

        if ($payment_method == 'credit_card') {
            $validator
                ->notBlank('card_id')
                ->add('card_id', 'check_card', [
                    'rule' => function($value) use($customer_id) {
                        return $this->CustomerCards->find()
                                ->where([
                                    'customer_id' => $customer_id,
                                    'id' => $value
                                ])
                                ->count() > 0;
                    },
                    'message' => 'Silahkan masukan credit credit card'
                ])
                ->notBlank('token');

            if (!$this->request->getData('card_id')) {

                $validator->requirePresence('card_number')
                    ->notBlank('card_number')
                    ->creditCard('card_number')
                    ->requirePresence('card_exp_month')
                    ->notBlank('card_exp_month')
                    ->minLength('card_exp_month', 2)
                    ->maxLength('card_exp_month', 2)
                    ->numeric('card_exp_month')
                    ->requirePresence('card_exp_year')
                    ->notBlank('card_exp_year')
                    ->minLength('card_exp_year', 4)
                    ->maxLength('card_exp_year', 4)
                    ->numeric('card_exp_year');
            }

            $validator
                ->requirePresence('cvv')
                ->notBlank('cvv')
                ->numeric('cvv')
                ->maxLength('cvv', 3)
                ->minLength('cvv', 3);
        }

        $error = $validator->errors($getData);
        if (!$error) {

            $payment = null;
            /**
             * @var \App\Model\Entity\CustomerDigitalInquiry $inquiryEntity
             */
            $inquiryEntity = null;

            /**
             * @var \App\Model\Entity\DigitalDetail $digitalDetailEntity
             */
            $digitalDetailEntity = null;

            //get customer
            $customerEntity = null;
            try {
                $customerEntity = $this->Customers->get($this->Authenticate->getId());
            } catch(\Exception $e) {

            }

            //check is inquiry_id
            if ($inquiry_id = $this->request->getData('inquiry_id')) {
                $inquiryEntity = $this->CustomerDigitalInquiry->find()
                    ->where([
                        'customer_id' => $this->Authenticate->getId(),
                        'id' => $inquiry_id,
                        'status' => 0
                    ])
                    ->first();
                if ($inquiryEntity) {
                    if ($code = $inquiryEntity->get('code')) {
                        $digitalDetailEntity = $this->DigitalDetails->find()
                            ->where([
                                'code' => $code
                            ])
                            ->contain([
                                'Digitals'
                            ])
                            ->first();
                        if ($digitalDetailEntity) {
                            $gross_total = $digitalDetailEntity->get('price');
                            $total = $gross_total;
                            $trx->addItem($inquiry_id, $digitalDetailEntity->get('price'), 1, $digitalDetailEntity->get('name'));
                        }

                    }

                }

            }




            $use_point = (int)$this->request->getData('use_point');

            $data['payment_method'] = $payment_method;
            $data['payment_amount'] = $trx->getAmount();


            switch ($payment_method) {
                case 'credit_card':
                    //for credit card

                    //request new token for gross_amount from saved card

                    $customer_card_entity = $this->CustomerCards->find()
                        ->where([
                            'customer_id' => $customer_id,
                            'id' => $this->request->getData('card_id')
                        ])
                        ->first();

                    if ($customer_card_entity) {

                        $payment = new CreditCard();
                        $payment->setToken($this->request->getData('token'))
                            ->saveToken(true)
                            //->setAuthentication(true)
                            ->setCustomer(
                                $customerEntity->get('email'),
                                $customerEntity->get('first_name'),
                                $customerEntity->get('last_name'),
                                $customerEntity->get('phone')
                            )
                            ->setBillingAddress()
                            ->setShippingFromBilling();




                    } else {}

                    break;

                case 'bca_va':
                    //for bca
                    $payment = (new BcaVirtualAccount(1111111))
                        ->setSubCompanyCode(1111);
                    break;

                case 'mandiri_billpayment':
                    $payment = (new MandiriBillPayment());
                    break;

                case 'permata_va':
                    //for permata
                    $full_name = trim($customerEntity->get('first_name') . ' ' . $customerEntity->get('last_name'));
                    $full_name = empty($full_name) ? $customerEntity->get('username') : $full_name;
                    $payment = (new PermataVirtualAccount())
                        ->setRecipientName($full_name);
                    break;

                case 'bni_va':
                    $payment = new BniVirtualAccount('111111');
                    break;

                case 'bca_klikpay':
                    $payment = new BcaKlikPay();
                    break;

                case 'mandiri_clickpay':
                    $token = (new \App\Lib\MidTrans\CreditCardToken())
                        ->setCardNumber('4111 1111 1111 1111')
                        ->request($trx->getAmount());
                    if ($token->status_code == 200) {
                        $payment = new MandiriClickPay($token->token_id, '54321', '000000');
                    }
                    break;

                case 'gopay':
                    $payment = new Gopay($this->request->getData('callback_url'));
                    break;

                default:
                    $payment = null;
                    break;
            }


            $request = new Request($payment);
            $request->addTransaction($trx);




            if (!$request->isCreditCard()) {
                $request->setCustomer(
                    $customerEntity->get('email'),
                    $customerEntity->get('first_name'),
                    $customerEntity->get('last_name'),
                    $customerEntity->get('phone')
                );
            }

            //debug(json_encode($request->toObject()));

            $orderEntity = $this->Orders->newEntity([
                'invoice' => $invoice,
                'order_type' => 2,
                'customer_id' => $customer_id,
                'province_id' => null,
                'city_id' => null,
                'subdistrict_id' => null,
                'address' => '',
                'use_point' => 0,
                'gross_total' => $gross_total,
                'total' => $total,
                'voucher_id' => null
            ]);

            $process_save_order = true;
            $process_payment_charge = false;

            $this->Orders->getConnection()->begin();


            try {
                if (!$this->Orders->save($orderEntity)) {
                    $process_save_order = false;
                }
            } catch(\Exception $e) {
                $this->setResponse($this->response->withStatus(406, 'Proses payment gagal, gagal menyimpan data transaksi'));
                $process_save_order = false;
            }



            if ($process_save_order) {
                //process charge exception credit card
                try {
                    $charge = $this->MidTrans->charge($request);
                    /*
                     * status_code 200 is success and using credit card
                     * status_code 201 is pending and using gopay, virtual_account, clickpay
                     */
                    if ($charge && isset($charge['status_code'])) {
                        switch ($charge['status_code']) {
                            case 200:
                                $data['payment_status'] = 'success';
                                $data['payment'] = $charge;
                                $process_payment_charge = true;
                                break;
                            case 201:
                                //process pending need response to frontend
                                $data['payment_status'] = 'pending';
                                $data['payment'] = $charge;
                                $process_payment_charge = true;
                                break;
                            default:
                                $data['payment_status'] = 'failed';
                                $this->setResponse($this->response->withStatus(406, 'Proses payment gagal 1'));
                                break;
                        }


                        if (isset($charge['payment_type'])) {
                            switch($charge['payment_type']) {
                                case 'bank_transfer':
                                    if (isset($charge['va_numbers'])) {
                                        foreach($charge['va_numbers'] as $va) {
                                            $charge['va_number'] = $va['va_number'];
                                            $charge['bank'] = $va['bank'];
                                        }
                                    } else if (isset($charge['permata_va_number'])) {
                                        $charge['va_number'] = $charge['permata_va_number'];
                                        $charge['bank'] = 'permata';
                                    }
                                    break;
                            }
                        }

                        //debug($charge);
                    }

                } catch(\Exception $e) {
                    $process_payment_charge = false;

                }
            }


            if ($process_save_order && $process_payment_charge) {
                $transactionEntity = $this->Transactions->newEntity($charge);
                if ($transactionEntity->payment_type && $orderEntity->get('id')) {
                    $transactionEntity->set('raw_response', json_encode($charge));
                    $transactionEntity->set('order_id', $orderEntity->get('id'));
                    $this->Transactions->save($transactionEntity);

                    //process save sepulsa
                    switch($this->request->getData('type')) {
                        case 'pulsa':

                            $inquiryEntity->set('status', true);
                            $this->CustomerDigitalInquiry->save($inquiryEntity);

                            $orderDigitalEntity = $this->Orders->OrderDigitals->newEntity([
                                'order_id' => $orderEntity->get('id'),
                                'digital_detail_id' => $digitalDetailEntity->get('id'),
                                'customer_number' => $inquiryEntity->get('customer_number'),
                                'price' => $digitalDetailEntity->get('price'),
                                'bonus_point' => $digitalDetailEntity->get('point'),
                                'status' => 0
                            ]);

                            $this->Orders->OrderDigitals->save($orderDigitalEntity);

                            break;
                    }
                }

                $this->Orders->getConnection()->commit();
            } else {
                $this->Orders->getConnection()->rollback();
                $this->setResponse($this->response->withStatus(406, 'Proses payment gagal'));
            }


        } else {
            $this->setResponse($this->response->withStatus(404, 'Cannot process payment'));
        }




        $this->set(compact('data', 'error'));
    }

    public function index()
    {
        $this->request->allowMethod('get');

        $type = $this->request->getQuery('type');

        switch ($type) {
            case 'pulsa':
                $data = $this->CustomerDigitalInquiry->find()
                    ->where([
                        'id' => $this->request->getQuery('inquiry_id'),
                        'customer_id' => $this->Authenticate->getId(),
                        'status' => 0
                    ])
                    ->map(function(\App\Model\Entity\CustomerDigitalInquiry $row) {

                        $row->gross_total = 0;
                        $row->total = 0;
                        $row->bonus_point = 0;

                        if ($code = $row->get('code')) {
                            $digital = $this->DigitalDetails->find()
                                ->where([
                                    'code' => $code
                                ])
                                ->contain([
                                    'Digitals'
                                ])
                                ->first();

                            $row->gross_total = $digital->get('price');
                            $row->total = $digital->get('price');
                            $row->bonus_point = $digital->get('point');

                            $row->digital_details = $digital;
                        }

                        return $row;
                    })
                    ->first();

                if (!$data) {
                    $this->setResponse($this->response->withStatus(406, 'Inquiry not found'));
                }

                break;

            default:
                $this->setResponse($this->response->withStatus(406, 'Invalid digital product'));
                break;
        }

        $this->set(compact('data'));

    }



}
