<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 18:36
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;


class MandiriBillPayment extends PaymentRequest
{

    public $payment_type = "echannel";
    public $echannel = [];


    public function __construct()
    {
        $this->echannel['bill_info1'] = 'Payment For:';
        $this->echannel['bill_info2'] = 'debt';
        return $this;
    }

    public function setBillInfo($info1, $info2)
    {
        $this->echannel['bill_info1'] = $info1;
        $this->echannel['bill_info2'] = $info2;
        return $this;
    }
}