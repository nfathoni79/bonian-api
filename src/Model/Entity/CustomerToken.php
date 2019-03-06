<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerToken Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string $uuid
 * @property int $activated
 * @property string|null $ip
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerToken extends Entity
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
        'uuid' => true,
        'activated' => true,
        'ip' => true,
        'created' => true,
        'modified' => true,
        'customer' => true
    ];
}
