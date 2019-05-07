<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductRatingImage Entity
 *
 * @property int $id
 * @property int $product_rating_id
 * @property string|null $name
 * @property string|null $dir
 * @property int|null $size
 * @property string|null $type
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\ProductRating $product_rating
 */
class ProductRatingImage extends Entity
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
        'product_rating_id' => true,
        'name' => true,
        'dir' => true,
        'size' => true,
        'type' => true,
        'created' => true,
        'product_rating' => true
    ];
}
