<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerBuyGroup Entity
 *
 * @property int $id
 * @property int $product_group_id
 * @property int $customer_id
 * @property string $name
 *
 * @property \App\Model\Entity\ProductGroup $product_group
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\CustomerBuyGroupDetail[] $customer_buy_group_details
 */
class CustomerBuyGroup extends Entity
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
        'customer_id' => true,
        'name' => true,
        'product_group' => true,
        'customer' => true,
        'customer_buy_group_details' => true
    ];
}
