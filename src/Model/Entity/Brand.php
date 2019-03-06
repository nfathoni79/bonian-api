<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Brand Entity
 *
 * @property int $id
 * @property int|null $product_category_id
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 * @property string $name
 *
 * @property \App\Model\Entity\ProductCategory $product_category
 * @property \App\Model\Entity\ParentBrand $parent_brand
 * @property \App\Model\Entity\ChildBrand[] $child_brands
 * @property \App\Model\Entity\Product[] $products
 */
class Brand extends Entity
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
        'product_category_id' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'name' => true,
        'product_category' => true,
        'parent_brand' => true,
        'child_brands' => true,
        'products' => true
    ];
}
