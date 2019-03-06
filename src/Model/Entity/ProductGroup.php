<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductGroup Entity
 *
 * @property int $id
 * @property string $name
 * @property int $value
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\CustomerBuyGroup[] $customer_buy_groups
 * @property \App\Model\Entity\ProductGroupDetail[] $product_group_details
 */
class ProductGroup extends Entity
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
        'value' => true,
        'date_start' => true,
        'date_end' => true,
        'status' => true,
        'created' => true,
        'customer_buy_groups' => true,
        'product_group_details' => true
    ];
}
