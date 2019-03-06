<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductToCategory Entity
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_category_id
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ProductCategory $product_category
 */
class ProductToCategory extends Entity
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
        'product_category_id' => true,
        'product' => true,
        'product_category' => true
    ];
}
