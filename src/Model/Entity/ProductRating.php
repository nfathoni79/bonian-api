<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductRating Entity
 *
 * @property int $id
 * @property int|null $order_detail_product_id
 * @property int|null $product_id
 * @property int|null $customer_id
 * @property int|null $rating
 * @property string|null $comment
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Customer $customer
 */
class ProductRating extends Entity
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
        'product_id' => true,
        'customer_id' => true,
        'rating' => true,
        'comment' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
        'customer' => true
    ];
}
