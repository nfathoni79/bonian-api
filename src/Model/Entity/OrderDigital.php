<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderDigital Entity
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $digital_detail_id
 * @property string|null $customer_number
 * @property float|null $price
 * @property string|null $raw_response
 * @property int $bonus_point
 * @property int status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\DigitalDetail $digital_detail
 * @property \App\Model\Entity\Order $order
 */
class OrderDigital extends Entity
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
        'order_id' => true,
        'digital_detail_id' => true,
        'customer_number' => true,
        'price' => true,
        'raw_response' => true,
        'bonus_point' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'digital_detail' => true,
        'order' => true
    ];
}
