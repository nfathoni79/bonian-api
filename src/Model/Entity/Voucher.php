<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Voucher Entity
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $code_voucher
 * @property \Cake\I18n\FrozenTime $date_start
 * @property \Cake\I18n\FrozenTime $date_end
 * @property int $qty
 * @property int $stock
 * @property int $type
 * @property int $point
 * @property int $percent
 * @property int $value
 * @property string $tos
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
        'name' => true,
        'slug' => true,
        'code_voucher' => true,
        'date_start' => true,
        'date_end' => true,
        'qty' => true,
        'stock' => true,
        'type' => true,
        'point' => true,
        'percent' => true,
        'value' => true,
        'tos' => true,
        'status' => true,
        'orders' => true
    ];
}
