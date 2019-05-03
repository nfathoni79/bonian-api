<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 18:53
 */

namespace App\Lib\MidTrans\Payment;

use App\Lib\MidTrans\PaymentRequest;

class BcaKlikPay extends PaymentRequest
{
    public $payment_type = "bca_klikpay";
    public $bca_klikpay = [];

    public function __construct()
    {
        $this->bca_klikpay['description'] = 'Pembelian barang';
        return $this;
    }

    public function setDescription($description)
    {
        $this->bca_klikpay['description'] = $description;
        return $this;
    }

}