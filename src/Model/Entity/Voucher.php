<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Voucher Entity
 *
 * @property int $id
 * @property string $code_voucher
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property int $qty
 * @property int $type
 * @property float $value
 * @property int $status
 *
 * @property \App\Model\Entity\Order[] $orders
 */
class Voucher extends Entity
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
        'code_voucher' => true,
        'date_start' => true,
        'date_end' => true,
        'qty' => true,
        'type' => true,
        'value' => true,
        'status' => true,
        'orders' => true
    ];
}
