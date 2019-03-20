<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderDetail Entity
 *
 * @property int $id
 * @property int $order_id
 * @property int $branch_id
 * @property int $courrier_id
 * @property string $awb
 * @property int $province_id
 * @property int $city_id
 * @property int $subdistrict_id
 * @property float $product_price
 * @property string $shipping_code
 * @property string $shipping_service
 * @property int $shipping_weight
 * @property float $shipping_cost
 * @property float $total
 * @property int $order_status_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Order $order
 * @property \App\Model\Entity\Branch $branch
 * @property \App\Model\Entity\Courrier $courrier
 * @property \App\Model\Entity\Subdistrict $subdistrict
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\OrderStatus $order_status
 * @property \App\Model\Entity\Chat[] $chats
 * @property \App\Model\Entity\OrderDetailProduct[] $order_detail_products
 * @property \App\Model\Entity\OrderShippingDetail[] $order_shipping_details
 */
class OrderDetail extends Entity
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
        'branch_id' => true,
        'courrier_id' => true,
        'awb' => true,
        'province_id' => true,
        'city_id' => true,
        'subdistrict_id' => true,
        'product_price' => true,
        'shipping_code' => true,
        'shipping_service' => true,
        'shipping_weight' => true,
        'shipping_cost' => true,
        'total' => true,
        'order_status_id' => true,
        'created' => true,
        'modified' => true,
        'order' => true,
        'branch' => true,
        'courrier' => true,
        'province' => true,
        'subdistrict' => true,
        'city' => true,
        'order_status' => true,
        'chats' => true,
        'order_detail_products' => true,
        'order_shipping_details' => true
    ];
}
