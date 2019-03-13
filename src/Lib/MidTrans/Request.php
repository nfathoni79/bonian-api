<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/03/2019
 * Time: 13:41
 */

namespace App\Lib\MidTrans;

/**
 * Class Request
 * @package App\Lib\MidTrans
 */
class Request
{
    protected $payment_type = null;
    protected $credit_card = null;
    protected $bank_transfer = [];
    protected $transaction_details = [];
    protected $customer_details = [];
    protected $item_details = [];


    /**
     * Request constructor.
     * payment_type: bank_transfer, credit_card, echannel, gopay
     * @param $payment_type
     */
    public function __construct($payment_type)
    {
        $this->payment_type = $payment_type;
        return $this;
    }

    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * @return bool
     */
    public function isCreditCard()
    {
        return $this->payment_type === 'credit_card';
    }

    /**
     * @return bool
     */
    public function isSavedToken()
    {
        if ($this->isCreditCard()) {
            return boolval($this->credit_card['save_token_id']) == 1;
        }
    }

    public function addTransaction(Transaction $transaction)
    {
        foreach($transaction->toObject() as $key => $val) {
            $this->{$key} = $val;
        }
        return $this;
    }

    public function setCreditCard($token_id, $save_token_id = null)
    {
        $this->credit_card = array_filter([
            'token_id' => $token_id,
            'save_token_id' => $save_token_id
        ]);
        return $this;
    }

    /**
     * @param $bank
     * @param null $va_number
     * @return $this
     */
    public function setBankTransfer($bank, $va_number = null)
    {
        $this->bank_transfer = array_filter([
            'bank' => $bank,
            'va_number' => $va_number
        ]);

        return $this;
    }

    /**
     * recipient name using for permata virtual account
     */
    public function setRecipientName($name)
    {
        if (strtolower($this->bank_transfer['bank']) == 'permata') {
            $this->bank_transfer['permata'] = [
                'recipient_name' => $name
            ];
        }
        return $this;
    }

    /**
     * @param $url
     */
    public function setGopayCallback($url)
    {
        if ($this->payment_type == 'gopay') {
            $this->gopay = [
                'enable_callback' => true,
                'callback_url' => $url
            ];
        }
    }

    /**
     * this request using for bca virtual account
     * @param $company_code
     * @return $this
     */
    public function setSubCompanyCode($company_code)
    {
        if (strtolower($this->bank_transfer['bank']) == 'bca') {
            $this->bank_transfer['bca'] = [
                'sub_company_code' => $company_code
            ];
        }
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

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    protected function validRequest()
    {
        if ($this->payment_type == 'credit_card') {
            unset($this->bank_transfer);
        }

        if ($this->payment_type == 'bank_transfer') {
            unset($this->credit_card);
        }
    }

    public function __toString()
    {
        $this->validRequest();
        return json_encode(array_filter(get_object_vars($this)));
    }

    public function toObject()
    {
        $this->validRequest();
        return array_filter(get_object_vars($this));
    }

}