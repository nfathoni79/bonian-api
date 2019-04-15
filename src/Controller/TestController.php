<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;
use App\Lib\MidTrans\Token;
use App\Lib\MidTrans\Request;
use App\Lib\MidTrans\Transaction;

/**
 * Class TestController
 * @property \App\Model\Table\CustomerMutationPointsTable $CustomerMutationPoints
 * @property \App\Model\Table\CustomerMutationAmountsTable $CustomerMutationAmounts
 * @property \App\Controller\Component\MidTransComponent $MidTrans
 * @package App\Controller
 */
class TestController extends AppController
{

    public function initialize()
    {
        $config = Configure::read('Midtrans');
        $this->merchant_id = $config['merchantid'];
        $this->client_key = $config['clientKey'];
        $this->server_key = $config['serverKey'];
        $this->loadComponent('MidTrans');

        $this->loadComponent('GenerationsTree');
        $this->loadModel('CustomerMutationPoints');
        $this->loadModel('CustomerMutationAmounts');
    }
    public function sponsor(){
        // $this->autoRender = false;

        // $this->GenerationsTree->save('D5FLFTBQDT', 'RWAMGKBSZV');
        // exit;
    }
    public function mutasi(){

//
//        $this->CustomerMutationAmounts->saving('14','1', '-500','Test mutation'); //mutation amount
        // $this->CustomerMutationPoints->saving('14','1', '-10','Test mutation'); //mutation point
    }
    public function oke()
    {
        /*
        $trx = new Transaction('ord-0019-x92');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke');

        try {

            $request = new Request('credit_card');
            $request->addTransaction($trx);

            $request->setCustomer(
                'iwaninfo@gmail.com',
                'Ridwan',
                'Rumi',
                '08112823746'
            )
            ->setBillingAddress()
            ->setShippingFromBilling();

            $token = $this->MidTrans->createToken(new Token(
                '4411 1111 1111 1118',
                '01',
                '20',
                '123'
            ), $trx->getAmount());

            if ($token['status_code'] == 200) {
                $request->setCreditCard($token['token_id'], true);

                $charge = $this->MidTrans->charge($request);
                debug($charge);
            }

            debug($request->toObject());

        } catch(\Exception $e) {

        }
        */

        /*
        $trx = new Transaction('ord-0019-x93');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke');
        $request = new Request('bank_transfer');
        $request->addTransaction($trx);

        $request->setCustomer(
            'iwaninfo@gmail.com',
            'Ridwan',
            'Rumi',
            '08112823746'
        );

        $request->setBankTransfer('bca')
            ->setSubCompanyCode('1111');

        $charge = $this->MidTrans->charge($request);
        debug($charge);

        debug($request->toObject());
        */

        /*
         * sample request for gopay
        $trx = new Transaction('ord-0019-x94');
        $trx->addItem(1, 2500, 1, 'barang oke');
        $trx->addItem(2, 2500, 1, 'barang oke');
        $request = new Request('gopay');
        $request->addTransaction($trx);

        $request->setCustomer(
            'iwaninfo@gmail.com',
            'Ridwan',
            'Rumi',
            '08112823746'
        );

        $request->setGopayCallback('http://localhost/2018');

        $charge = $this->MidTrans->charge($request);
        debug($charge);

        debug($request->toObject());
        */

        exit;
    }

    public function index(){


        //Set Your server key
        \Veritrans_Config::$serverKey = $this->server_key;
        // Uncomment for production environment
        // Veritrans_Config::$isProduction = true;
        // Enable sanitization
        \Veritrans_Config::$isSanitized = true;
        // Enable 3D-Secure
        \Veritrans_Config::$is3ds = true;
        // Required
        $transaction_details = array(
            'order_id' => rand(),
            'gross_amount' => 94000, // no decimal allowed for creditcard
        );
        // Optional
        $item1_details = array(
            'id' => 'a1',
            'price' => 18000,
            'quantity' => 3,
            'name' => "Apple"
        );
        // Optional
        $item2_details = array(
            'id' => 'a2',
            'price' => 20000,
            'quantity' => 2,
            'name' => "Orange"
        );
        // Optional
        $item_details = array ($item1_details, $item2_details);
        // Optional
        $billing_address = array(
            'first_name'    => "Andri",
            'last_name'     => "Litani",
            'address'       => "Mangga 20",
            'city'          => "Jakarta",
            'postal_code'   => "16602",
            'phone'         => "081122334455",
            'country_code'  => 'IDN'
        );
        // Optional
        $shipping_address = array(
            'first_name'    => "Obet",
            'last_name'     => "Supriadi",
            'address'       => "Manggis 90",
            'city'          => "Jakarta",
            'postal_code'   => "16601",
            'phone'         => "08113366345",
            'country_code'  => 'IDN'
        );
        // Optional
        $customer_details = array(
            'first_name'    => "Andri",
            'last_name'     => "Litani",
            'email'         => "andri@litani.com",
            'phone'         => "081122334455",
            'billing_address'  => $billing_address,
            'shipping_address' => $shipping_address
        );
        // Optional, remove this to display all available payment methods
        $enable_payments = array('credit_card','cimb_clicks','mandiri_clickpay','echannel');
        // Fill transaction details
        $transaction = array(
            'enabled_payments' => $enable_payments,
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );
        $snapToken = \Veritrans_Snap::getSnapToken($transaction);
        echo "snapToken = ".$snapToken;

    }




}
