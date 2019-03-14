<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 19:25
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;

class MandiriClickPay extends PaymentRequest
{
    public $payment_type = "mandiri_clickpay";
    public $mandiri_clickpay = [];

    public function __construct($token_id, $input3, $token)
    {
        $this->mandiri_clickpay = [
            'token_id' => $token_id,
            'input3' => $input3,
            'token' => $token
        ];
        return $this;
    }
}