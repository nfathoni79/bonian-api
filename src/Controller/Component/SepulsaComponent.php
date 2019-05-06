<?php
namespace AdminPanel\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Cake\Log\Log;
use Cake\Core\Configure;

/**
 * NevixCoin component
 */
class SepulsaComponent extends Component
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
        $sepulsa = Configure::read('Sepulsa');
        $this->base_uri = $sepulsa['url'];
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->base_uri,
            // You can set any number of default request options.
            'timeout'  => 30.0,
            'auth' => $sepulsa['auth'],
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
        Log::info($this->_response, ['scope' => ['sepulsa']]);
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
     * @param $type
     * @param null $operator
     * @param null $nominal
     * @param null $page
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function getProduct($type, $operator = null, $nominal = null, $page = null)
    {
        $this->_response = $this->makeRequest()->get('product.json', [
            'query' => array_filter([
                'type' => $type,
                'operator' => $operator,
                'nominal' => $nominal,
                'page' => $page
            ])
        ])->getBody();
        return $this->_getResponse();
    }

    public function getBalance()
    {
        $this->_response = $this->makeRequest()->get('getBalance')->getBody();
        return $this->_getResponse();
    }

    /**
     * array request
     * @param array $request
     * @param null $page
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function getTransactions(array $request = [],  $page = null)
    {
        $defaultRequest = [
            'status' => null,
            'type' => 'mobile',
            'customer_number' => null,
            'order_id' => null,
            'transaction_id' => null,
            'meter_number' => null,
            'page' => $page
        ];

        $request += $defaultRequest;

        $this->_response = $this->makeRequest()->get('transaction.json', [
            'query' => array_filter($request)
        ])->getBody();
        return $this->_getResponse();
    }

    /**
     * @param $transaction_id
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function getTransactionDetail($transaction_id) {
        $this->_response = $this->makeRequest()->get("transaction/{$transaction_id}.json")->getBody();
        return $this->_getResponse();
    }

    /**
     * @param $customer_number
     * @param $product_id
     * @param null $order_id
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function createMobileTransaction($customer_number, $product_id, $order_id = null)
    {
        $this->_response = $this->makeRequest()->post('transaction/mobile.json', [
            RequestOptions::JSON =>  array_filter([
                    'customer_number' => $customer_number,
                    'product_id' => $product_id,
                    'order_id' => $order_id,
                ])
        ])->getBody();
        return $this->_getResponse();
    }

    /**
     * @param $transaction_id
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function getMobileTransaction($transaction_id)
    {
        $this->_response = $this->makeRequest()->get("transaction/mobile/{$transaction_id}.json")->getBody();
        return $this->_getResponse();
    }
}
