<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductPromotionImage Entity
 *
 * @property int $id
 * @property int $product_promotion_id
 * @property string $image
 * @property string $dimension
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\ProductPromotion $product_promotion
 */
class ProductPromotionImage extends Entity
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
        'product_promotion_id' => true,
        'image' => true,
        'dimension' => true,
        'created' => true,
        'product_promotion' => true
    ];
}
