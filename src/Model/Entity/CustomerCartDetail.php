<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerCartDetail Entity
 *
 * @property int $id
 * @property int $customer_cart_id
 * @property int $qty
 * @property int $product_id
 * @property int $product_option_price_id
 * @property int $product_option_stock_id
 * @property float $price
 * @property float $add_price
 * @property bool $in_flashsale
 * @property bool $in_groupsale
 * @property float $total
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\CustomerCart $customer_cart
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ProductOptionPrice $product_option_price
 * @property \App\Model\Entity\ProductOptionStock $product_option_stock
 */
class CustomerCartDetail extends Entity
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
        'customer_cart_id' => true,
        'qty' => true,
        'product_id' => true,
        'product_option_price_id' => true,
        'product_option_stock_id' => true,
        'price' => true,
        'add_price' => true,
        'in_flashsale' => true,
        'in_groupsale' => true,
        'total' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'customer_cart' => true,
        'product' => true,
        'product_option_price' => true,
        'product_option_stock' => true
    ];
}
