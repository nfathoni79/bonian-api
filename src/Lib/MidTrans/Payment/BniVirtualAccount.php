<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 18:44
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;


class BniVirtualAccount extends PaymentRequest
{
    public $payment_type = "bank_transfer";
    public $bank_transfer = [];

    public function __construct($va_number = null)
    {
        $this->bank_transfer['bank'] = 'bni';
        if ($va_number) {
            $this->bank_transfer['va_number'] = $va_number;
        }

        return $this;
    }
}