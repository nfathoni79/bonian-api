<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Cake\Log\Log;

/**
 * MidTrans component
 */
class MidTransComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var string
     */
    protected $base_uri = '';

    /**
     * @var \GuzzleHttp\Psr7\Stream
     */
    protected $_response = null;

    /**
     * Default Request
     * @var array
     */
    protected $_defaultRequest = [

    ];

    /**
     * initialize components
     * @param array $config
     *
     */
    public function initialize(array $config)
    {
        $this->_defaultRequest += $config;
    }

    /**
     * Initial Request
     * @return Client
     */
    public function makeRequest()
    {
        $midtrans = Configure::read('Midtrans');
        $this->base_uri = $midtrans['url'];
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->base_uri,
            // You can set any number of default request options.
            'timeout'  => 30.0,
            'auth' => [$midtrans['serverKey'], ''],
            'headers' => [
                'User-Agent' => 'zolaku/1.0'
            ]
        ]);
    }

    /**
     * @param bool $isJson
     * @return \Psr\Http\Message\StreamInterface|null
     */
    protected function _getResponse($isJson = true)
    {
        Log::info($this->_response, ['scope' => ['midtrans']]);
        return $isJson ? json_decode($this->_response, true) : $this->_response;
    }

    /**
     * @return \GuzzleHttp\Psr7\Stream
     */
    public function getResponse()
    {
        return $this->_response;
    }


    /**
     * The transaction amount NOTE: Don't add decimal
     * @param \App\Lib\MidTrans\Token $token
     * @param $amount
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function createToken(\App\Lib\MidTrans\Token $token, $amount)
    {
        $token = $token->getRequest();
        $token['client_key'] = Configure::read('Midtrans.clientKey');
        $token['gross_amount'] = $amount;

        $this->_response = $this->makeRequest()->get('v2/token', [
            'query' => $token
        ])->getBody();

        return $this->_getResponse();
    }


    /**
     *
     * cc
     * $trx = new Transaction('ord-0019-x92');
     * $trx->addItem(1, 2500, 1, 'barang oke');
     * $trx->addItem(2, 2500, 1, 'barang oke');
     *
     * try {
     *
     * $request = new Request('credit_card');
     * $request->addTransaction($trx);
     *
     * $request->setCustomer(
     * 'iwaninfo@gmail.com',
     * 'Ridwan',
     * 'Rumi',
     * '08112823746'
     * )
     * ->setBillingAddress()
     * ->setShippingFromBilling();
     *
     * $token = $this->MidTrans->createToken(new Token(
     * '4411 1111 1111 1118',
     * '01',
     * '20',
     * '123'
     * ), $trx->getAmount());
     *
     * if ($token['status_code'] == 200) {
     * $request->setCreditCard($token['token_id'], true);
     *
     * $charge = $this->MidTrans->charge($request);
     * debug($charge);
     * }
     *
     * debug($request->toObject());
     *
     * } catch(\Exception $e) {
     *
     * }
     *
     *
     * virtual account
     * $trx = new Transaction('ord-0019-x93');
     * $trx->addItem(1, 2500, 1, 'barang oke');
     * $trx->addItem(2, 2500, 1, 'barang oke');
     * $request = new Request('bank_transfer');
     * $request->addTransaction($trx);
     *
     * $request->setCustomer(
     * 'iwaninfo@gmail.com',
     * 'Ridwan',
     * 'Rumi',
     * '08112823746'
     * );
     *
     * $request->setBankTransfer('bca')
     * ->setSubCompanyCode('1111');
     *
     * $charge = $this->MidTrans->charge($request);
     *
     * gopay
     * $trx = new Transaction('ord-0019-x94');
     * $trx->addItem(1, 2500, 1, 'barang oke');
     * $trx->addItem(2, 2500, 1, 'barang oke');
     * $request = new Request('gopay');
     * $request->addTransaction($trx);

     * $request->setCustomer(
     *  'iwaninfo@gmail.com',
     *  'Ridwan',
     *  'Rumi',
     *  '08112823746'
     * );
     *
     * $request->setGopayCallback('http://localhost/2018');
     *
     * $charge = $this->MidTrans->charge($request);
     *
     * @param \App\Lib\MidTrans\Request $request
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function charge(\App\Lib\MidTrans\Request $request)
    {
        $this->_response = $this->makeRequest()->post('v2/charge', [
            RequestOptions::JSON =>  $request->toObject()
        ])->getBody();
        return $this->_getResponse();
    }


}
