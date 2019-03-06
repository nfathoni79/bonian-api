<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property string $invoice
 * @property int $customer_id
 * @property string $address
 * @property int $voucher_id
 * @property int $product_promotion_id
 * @property float $total
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Voucher $voucher
 * @property \App\Model\Entity\ProductPromotion $product_promotion
 * @property \App\Model\Entity\OrderDetail[] $order_details
 */
class Order extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'invoice' => true,
        'customer_id' => true,
        'address' => true,
        'voucher_id' => true,
        'product_promotion_id' => true,
        'total' => true,
        'created' => true,
        'customer' => true,
        'voucher' => true,
        'product_promotion' => true,
        'order_details' => true
    ];
}
