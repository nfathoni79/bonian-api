<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductCoupon Entity
 *
 * @property int $id
 * @property int|null $product_id
 * @property float $price
 * @property \Cake\I18n\FrozenDate $expired
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\CustomerCartCoupon[] $customer_cart_coupons
 */
class ProductCoupon extends Entity
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
        'product_id' => true,
        'price' => true,
        'expired' => true,
        'status' => true,
        'created' => true,
        'product' => true,
        'customer_cart_coupons' => true
    ];
}
