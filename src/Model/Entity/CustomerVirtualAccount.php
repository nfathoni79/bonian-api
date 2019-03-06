<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerVirtualAccount Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string $va_number
 * @property \Cake\I18n\FrozenTime $expired_date
 * @property string $status
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerVirtualAccount extends Entity
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
        'va_number' => true,
        'expired_date' => true,
        'status' => true,
        'customer' => true
    ];
}
