<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Generation Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 * @property int|null $customer_id
 * @property int|null $refferal_id
 * @property int $level
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer $customer
 */
class Generation extends Entity
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
        'customer_id' => true,
        'refferal_id' => true,
        'level' => true,
        'created' => true,
        'customer' => true
    ];
}
