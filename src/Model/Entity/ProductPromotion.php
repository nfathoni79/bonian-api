<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductPromotion Entity
 *
 * @property int $id
 * @property string $name
 * @property int $product_id
 * @property int $qty
 * @property int $min_qty
 * @property int $free_product_id
 * @property int $free_qty
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Order[] $orders
 * @property \App\Model\Entity\ProductPromotionImage[] $product_promotion_images
 */
class ProductPromotion extends Entity
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
        'name' => true,
        'product_id' => true,
        'qty' => true,
        'min_qty' => true,
        'free_product_id' => true,
        'free_qty' => true,
        'date_start' => true,
        'date_end' => true,
        'created' => true,
        'product' => true,
        'orders' => true,
        'product_promotion_images' => true
    ];
}
