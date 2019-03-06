<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductCategory Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $path
 * @property int|null $counter_view
 *
 * @property \App\Model\Entity\ProductCategory $parent_product_category
 * @property \App\Model\Entity\Attribute[] $attributes
 * @property \App\Model\Entity\Brand[] $brands
 * @property \App\Model\Entity\CustomerLogBrowsing[] $customer_log_browsings
 * @property \App\Model\Entity\ProductCategory[] $child_product_categories
 * @property \App\Model\Entity\ProductToCategory[] $product_to_categories
 */
class ProductCategory extends Entity
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
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'name' => true,
        'slug' => true,
        'description' => true,
        'path' => true,
        'counter_view' => true,
        'parent_product_category' => true,
        'attributes' => true,
        'brands' => true,
        'customer_log_browsings' => true,
        'child_product_categories' => true,
        'product_to_categories' => true
    ];
}
