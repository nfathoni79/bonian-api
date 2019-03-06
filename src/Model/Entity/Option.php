<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Option Entity
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 *
 * @property \App\Model\Entity\OptionValue[] $option_values
 * @property \App\Model\Entity\ProductOptionValueList[] $product_option_value_lists
 */
class Option extends Entity
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
        'sort_order' => true,
        'option_values' => true,
        'product_option_value_lists' => true
    ];
}
