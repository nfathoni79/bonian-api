<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 17:48
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;

class BcaVirtualAccount extends PaymentRequest
{
    public $payment_type = "bank_transfer";
    public $bank_transfer = [];
    public $bca = [];


    public function __construct($va_number)
    {
        $this->bank_transfer['bank'] = 'bca';
        $this->bank_transfer['va_number'] = $va_number;
        $this->bca['sub_company_code'] = '00000';
        return $this;
    }

    public function setSubCompanyCode($code)
    {
        $this->bca['sub_company_code'] = $code;
        return $this;
    }
}