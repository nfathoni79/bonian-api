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
    //protected $payment_type = null;
    //protected $credit_card = null;
    //protected $bank_transfer = [];
    protected $transaction_details = [];
    protected $customer_details = [];
    protected $item_details = [];



    /*public function __construct($payment_type)
    {
        $this->payment_type = $payment_type;
        return $this;
    }*/

    /**
     * Request constructor.
     * @param PaymentRequest $payment
     */
    public function __construct(PaymentRequest $payment)
    {
        foreach(get_object_vars($payment) as $key => $val) {
            $this->{$key} = $val;
        }
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



    public function setCustomer($email, $fist_name, $last_name, $phone)
    {
        $this->customer_details = array_merge($this->customer_details, [
            'first_name' => $fist_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone
        ]);
        return $this;
    }



    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }



    public function __toString()
    {
        return json_encode(array_filter(get_object_vars($this)));
    }

    public function toObject()
    {
        return array_filter(get_object_vars($this));
    }

}