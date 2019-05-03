<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 19:50
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;

class Gopay extends PaymentRequest
{
    public $payment_type = "gopay";
    public $gopay = [];

    public function __construct($callback_url = null)
    {
        if ($callback_url) {
            $this->gopay = [
                'enable_callback' => true,
                'callback_url' => $callback_url
            ];
        }

        return $this;
    }


}