<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/03/2019
 * Time: 10:58
 */

namespace App\Lib\MidTrans;


/**
 * Class Token
 * @package App\Lib\MidTrans
 */
class Token
{
    protected $card_number = null;
    protected $card_exp_month = null;
    protected $card_exp_year = null;
    protected $card_cvv = null;
    protected $secure = false;
    /**
     * "bni", "mandiri", "cimb", "bca", "bri", "maybank"
     * @var null
     */
    protected $bank = null;
    /**
     * installment_term "3", "6"
     * @var null
     */
    protected $installment_term = null;
    protected $point = null;
    protected $token_id = null;
    /**
     * type: authorize
     * @var null
     */
    protected $type = null;

    /**
     * Token constructor.
     * @param null $number
     * @param null $month
     * @param null $year
     * @param null $cvv
     */
    public function __construct($number = null, $month = null, $year = null, $cvv = null)
    {
        $this->card_number = $number;
        $this->card_exp_month = $month;
        $this->card_exp_year = $year;
        $this->card_cvv = $cvv;
        return $this;
    }

    /**
     * @param $saved_token
     * @return $this
     */
    public function setToken($saved_token)
    {
        $this->token_id = $saved_token;
        if ($saved_token) {
            $this->card_number = null;
            $this->card_exp_month = null;
            $this->card_exp_year = null;
            $this->bank = null;
            $this->installment_term = null;
        }

        return $this;
    }

    /**
     * @param $cvv
     */
    public function setCvv($cvv)
    {
        $this->card_cvv = $cvv;
    }

    /**
     * @param $secure bool
     * @return $this
     */
    public function setSecure($secure)
    {
        $this->secure = $secure ? 'true' : 'false';
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