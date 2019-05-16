<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderShippingDetail Entity
 *
 * @property int $id
 * @property int $order_detail_id
 * @property int $status
 * @property string $note
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\OrderDetail $order_detail
 */
class OrderShippingDetail extends Entity
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
        'order_detail_id' => true,
        'status' => true,
        'note' => true,
        'created' => true,
        'order_detail' => true
    ];
}
