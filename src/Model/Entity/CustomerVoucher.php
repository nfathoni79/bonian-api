<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerVoucher Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $voucher_id
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Voucher $voucher
 */
class CustomerVoucher extends Entity
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
        'voucher_id' => true,
        'status' => true,
        'created' => true,
        'customer' => true,
        'voucher' => true
    ];
}
