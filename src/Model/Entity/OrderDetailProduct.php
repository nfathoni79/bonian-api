<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderDetailProduct Entity
 *
 * @property int $id
 * @property int $order_detail_id
 * @property int $product_id
 * @property int $product_option_value_id
 * @property int $qty
 * @property float $price
 * @property float $total
 * @property string $comment
 * @property int $product_option_price_id
 * @property int $product_option_stock_id
 * @property bool $in_flashsale
 * @property bool $in_groupsale
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\OrderDetail $order_detail
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\OptionValue $option_value
 */
class OrderDetailProduct extends Entity
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
        'order_detail_id' => true,
        'product_id' => true,
        'product_option_value_id' => true,
        'qty' => true,
        'price' => true,
        'total' => true,
        'comment' => true,
        'product_option_price_id' => true,
        'product_option_stock_id' => true,
        'in_flashsale' => true,
        'in_groupsale' => true,
        'created' => true,
        'order_detail' => true,
        'product' => true,
        'option_value' => true
    ];
}
