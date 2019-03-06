<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductGroupDetail Entity
 *
 * @property int $id
 * @property int $product_group_id
 * @property int $product_id
 * @property float|null $price_sale
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\ProductGroup $product_group
 * @property \App\Model\Entity\Product $product
 */
class ProductGroupDetail extends Entity
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
        'product_group_id' => true,
        'product_id' => true,
        'price_sale' => true,
        'created' => true,
        'product_group' => true,
        'product' => true
    ];
}
