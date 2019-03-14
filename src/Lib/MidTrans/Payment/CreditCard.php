<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 14:21
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;

class CreditCard extends PaymentRequest
{

    public $payment_type = "credit_card";
    public $credit_card = [];
    public $customer_details = [];


    public function __construct()
    {
        return $this;
    }

    public function setToken($token)
    {
        $this->credit_card['token_id'] = $token;
        return $this;
    }

    public function saveToken($token)
    {
        if (boolval($token) == 1) {
            $this->credit_card['save_token_id'] = true;
        }

        return $this;
    }

    public function setInstallment($bank, $installment_term)
    {
        $this->credit_card['bank'] = $bank;
        $this->credit_card['installment_term'] = $installment_term;

        return $this;
    }

    public function setCustomer($email, $fist_name, $last_name, $phone)
    {
        $this->customer_details = [
            'first_name' => $fist_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone
        ];
        return $this;
    }

    public function setBillingAddress($address = "Sudirman", $city = "Jakarta", $postal_code = 12190, $country_code = "IDN")
    {
        $this->customer_details['billing_address'] = [
            'first_name' => $this->customer_details['first_name'],
            'last_name' => $this->customer_details['last_name'],
            'email' => $this->customer_details['email'],
            'phone' => $this->customer_details['phone'],
            'address' => $address,
            'city' => $city,
            'postal_code' => $postal_code,
            'country_code' => $country_code
        ];
        return $this;
    }


    public function setShippingFromBilling()
    {
        $this->customer_details['shipping_address'] = $this->customer_details['billing_address'];
    }

    public function setShippingAddress($address = "Sudirman", $city = "Jakarta", $postal_code = 12190, $country_code = "IDN")
    {
        $this->customer_details['shipping_address'] = [
            'first_name' => $this->customer_details['first_name'],
            'last_name' => $this->customer_details['last_name'],
            'email' => $this->customer_details['email'],
            'phone' => $this->customer_details['phone'],
            'address' => $address,
            'city' => $city,
            'postal_code' => $postal_code,
            'country_code' => $country_code
        ];
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