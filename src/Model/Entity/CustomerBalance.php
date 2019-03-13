<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerBalance Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property float $balance
 * @property float $point
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerBalance extends Entity
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
        'balance' => true,
        'point' => true,
        'modified' => true,
        'modified_point' => true,
        'customer' => true
    ];
}
