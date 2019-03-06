<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductImageSize Entity
 *
 * @property int $id
 * @property int $product_image_id
 * @property string $dimension
 * @property string $path
 * @property int|null $size
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\ProductImage $product_image
 */
class ProductImageSize extends Entity
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
        'product_image_id' => true,
        'dimension' => true,
        'path' => true,
        'size' => true,
        'created' => true,
        'product_image' => true
    ];
}
