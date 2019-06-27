<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerShareProduct Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $product_id
 * @property int|null $order_id
 * @property float|null $percentage
 * @property boolean|null $credited
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Order $order
 */
class CustomerShareProduct extends Entity
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
        'product_id' => true,
        'order_id' => true,
        'percentage' => true,
        'credited' => true,
        'created' => true,
        'customer' => true,
        'product' => true,
        'order' => true
    ];
}
