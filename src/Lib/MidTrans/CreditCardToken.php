<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 14:40
 */

namespace App\Lib\MidTrans;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Cake\Core\Configure;


class CreditCardToken
{
    protected $card_number = null;
    protected $card_exp_month = null;
    protected $card_exp_year = null;
    protected $card_cvv = null;
    protected $secure = false;
    /**
     * "bni", "mandiri", "cimb", "bca", "bri", "maybank"
     * @var null
     */
    protected $bank = null;
    /**
     * installment_term "3", "6"
     * @var null
     */
    protected $installment_term = null;
    protected $point = null;
    protected $token_id = null;
    /**
     * type: authorize
     * @var null
     */
    protected $type = null;

    /**
     * Token constructor.
     * @param null $number
     * @param null $month
     * @param null $year
     * @param null $cvv
     */
    public function __construct($number = null, $month = null, $year = null, $cvv = null)
    {
        $this->card_number = $number;
        $this->card_exp_month = $month;
        $this->card_exp_year = $year;
        $this->card_cvv = $cvv;
        return $this;
    }

    /**
     * @param $saved_token
     * @return $this
     */
    public function setToken($saved_token)
    {
        $this->token_id = $saved_token;
        if ($saved_token) {
            $this->card_number = null;
            $this->card_exp_month = null;
            $this->card_exp_year = null;
            $this->bank = null;
            $this->installment_term = null;
        }

        return $this;
    }

    public function setCardNumber($card_number)
    {
        $this->card_number = $card_number;
        return $this;
    }

    /**
     * @param $month string lead zero example 01,02 or 12
     * @return $this
     */
    public function setExpMonth($month)
    {
        $this->card_exp_month = str_pad($month, 2, '0', STR_PAD_LEFT);
        return $this;
    }

    /**
     * @param $year string|integer year 4 digit
     * @return $this
     */
    public function setExpYear($year)
    {
        $this->card_exp_year = $year;
        return $this;
    }


    public function setInstallment($bank, $installment_term)
    {
        $this->bank = $bank;
        $this->installment_term = $installment_term;
        return $this;
    }

    /**
     * * Normal Transaction 	card_number, card_cvv, card_exp_month, card_exp_year
     * 3D Secure Transaction 	card_number, card_cvv, card_exp_month, card_exp_year, secure, gross_amount 	bank
     * Installment 	card_number, card_cvv, card_exp_month, card_exp_year, secure, gross_amount, installment_term 	bank
     * Pre-authorization 	card_number, card_cvv, card_exp_month, card_exp_year, secure, gross_amount, type 	bank
     * Normal Two Clicks 	card_cvv, token_id
     * 3D Secure Two Clicks 	card_cvv, token_id, secure, gross_amount 	bank
     *
     *
     * @param $amount
     * @return \App\Lib\MidTrans\CreditCardTokenResponse
     */
    public function request($amount)
    {
        $midtrans = Configure::read('Midtrans');
        $client =  new Client([
            // Base URI is used with relative requests
            'base_uri' => $midtrans['url'],
            // You can set any number of default request options.
            'timeout'  => 30.0,
            'auth' => [$midtrans['serverKey'], ''],
            'headers' => [
                'User-Agent' => 'zolaku/1.0'
            ]
        ]);

        $token = $this->getRequest();
        $token['client_key'] = $midtrans['clientKey'];
        $token['gross_amount'] = $amount;


        $response = $client->get('v2/token', [
            'query' => $token
        ]);



        //$response = json_decode($response, true);

        return new CreditCardTokenResponse($response);

    }


    /**
     * @return string
     */
    public function register()
    {
        $midtrans = Configure::read('Midtrans');
        $client =  new Client([
            // Base URI is used with relative requests
            'base_uri' => $midtrans['url'],
            // You can set any number of default request options.
            'timeout'  => 30.0,
            'auth' => [$midtrans['serverKey'], ''],
            'headers' => [
                'User-Agent' => 'zolaku/1.0'
            ]
        ]);

        $token = $this->getRequest();
        $token['client_key'] = $midtrans['clientKey'];


        $response = $client->get('v2/card/register', [
            'query' => $token
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param $cvv
     * @return $this
     */
    public function setCvv($cvv)
    {
        $this->card_cvv = $cvv;
        return $this;
    }

    /**
     * @param $secure bool
     * @return $this
     */
    public function setSecure($secure)
    {
        $this->secure = $secure ? 'true' : 'false';
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode(array_filter(get_object_vars($this)));
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return array_filter(get_object_vars($this));
    }
}