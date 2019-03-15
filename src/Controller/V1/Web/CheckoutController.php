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

use Cake\I18n\Time;
use App\Lib\MidTrans\Token;
use Cake\Utility\Security;
use Cake\Core\Configure;


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCardsTable $CustomerCards
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CheckoutController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCards');

        $this->Auth->allow(['index']);
    }


    public function index()
    {
        //list checkout and default customer address

    }

    /**
     * payment process
     */
    public function payment()
    {

        $this->request->allowMethod(['post', 'put']);
        $trx = new Transaction('ord-0021-x10175');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke 2');


        $payment_type = 'gopay';
        switch ($payment_type) {
            case 'credit_card':
                //for credit card
                $payment = new CreditCard();
                $payment->setToken('441111lSmrlWhaoZtyTjOAscGBrc1118')
                    ->saveToken(true)
                    ->setInstallment('bca', 6)
                    ->setCustomer(
                        'iwaninfo@gmail.com',
                        'Ridwan',
                        'Rumi',
                        '08112823746'
                    )
                    ->setBillingAddress()
                    ->setShippingFromBilling();

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
                $payment = (new PermataVirtualAccount())
                    ->setRecipientName('Ridwan');
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
                    ->request(10000);
                if ($token->status_code == 200) {
                    $payment = new MandiriClickPay($token->token_id, '54321', '000000');
                }
                break;

            case 'gopay':
                $payment = new Gopay('http://php.net');
                break;
        }


        $request = new Request($payment);
        $request->addTransaction($trx);

        $request->setCustomer(
            'iwaninfo@gmail.com',
            'Ridwan',
            'Rumi',
            '0817123123'
        );


        $data = $this->MidTrans->charge($request);


        $this->set(compact('data'));
    }

}
