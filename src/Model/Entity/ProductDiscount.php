<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductDiscount Entity
 *
 * @property int $id
 * @property int $product_id
 * @property float $discount
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Product $product
 */
class ProductDiscount extends Entity
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
        'discount' => true,
        'date_start' => true,
        'date_end' => true,
        'created' => true,
        'modified' => true,
        'product' => true
    ];
}
