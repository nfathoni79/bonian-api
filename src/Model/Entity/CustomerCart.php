<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerCart Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\CustomerCartDetail[] $customer_cart_details
 */
class CustomerCart extends Entity
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
        'customer_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'customer_cart_details' => true
    ];
}
