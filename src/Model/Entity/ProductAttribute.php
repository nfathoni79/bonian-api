<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductAttribute Entity
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $attribute_name_id
 * @property int $attribute_id
 * @property string $description
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\AttributeName $attribute_name
 * @property \App\Model\Entity\Attribute $attribute
 */
class ProductAttribute extends Entity
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
        'attribute_name_id' => true,
        'attribute_id' => true,
        'description' => true,
        'product' => true,
        'attribute_name' => true,
        'attribute' => true
    ];
}
