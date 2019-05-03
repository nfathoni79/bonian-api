<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 18:18
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;


class PermataVirtualAccount extends PaymentRequest
{
    public $payment_type = "bank_transfer";
    public $bank_transfer = [];

    public function __construct()
    {
        $this->bank_transfer['bank'] = 'permata';
        return $this;
    }

    public function setRecipientName($name)
    {
        $this->bank_transfer['permata'] = [
            'recipient_name' => $name
        ];

        return $this;
    }
}