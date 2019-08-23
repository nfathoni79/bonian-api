<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/03/2019
 * Time: 14:07
 */

namespace App\Lib\MidTrans;

/**
 * Class Transaction
 * @package App\Lib\MidTrans
 */
class Transaction
{
    protected $transaction_details = [];
    protected $item_details = [];

    /**
     * Transaction constructor.
     * @param $order_id
     */
    public function __construct($order_id)
    {

        $this->transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => 0
        ];

        return $this;
    }

    /**
     * @param $id
     * @param $price
     * @param $quantity
     * @param $name
     * @return $this
     */
    public function addItem($id, $price, $quantity, $name)
    {
        array_push($this->item_details, [
            'id' => $id,
            'price' => $price,
            'quantity' => $quantity,
            'name' => $name
        ]);

        $this->transaction_details['gross_amount'] += $price * $quantity;

        return $this;
    }

    /**
     * @return array
     */
    public function getDetail()
    {
        return $this->transaction_details;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->transaction_details['gross_amount'];
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->transaction_details['order_id'];
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->item_details;
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
    public function toObject()
    {
        return get_object_vars($this);
    }
}