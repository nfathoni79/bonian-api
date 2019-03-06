<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Branch Entity
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property int $provice_id
 * @property int $city_id
 * @property int $subdistrict_id
 * @property float $latitude
 * @property float $longitude
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Province $province
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\Subdistrict $subdistrict
 * @property \App\Model\Entity\OrderDetail[] $order_details
 * @property \App\Model\Entity\ProductBranch[] $product_branches
 * @property \App\Model\Entity\ProductOptionStock[] $product_option_stocks
 * @property \App\Model\Entity\ProductStockMutation[] $product_stock_mutations
 * @property \App\Model\Entity\User[] $users
 */
class Branch extends Entity
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
        'address' => true,
        'phone' => true,
        'provice_id' => true,
        'city_id' => true,
        'subdistrict_id' => true,
        'latitude' => true,
        'longitude' => true,
        'created' => true,
        'modified' => true,
        'province' => true,
        'city' => true,
        'subdistrict' => true,
        'order_details' => true,
        'product_branches' => true,
        'product_option_stocks' => true,
        'product_stock_mutations' => true,
        'users' => true
    ];
}
