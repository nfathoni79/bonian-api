<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductOptionPrice Entity
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property \Cake\I18n\FrozenDate|null $expired
 * @property float $price
 * @property int|null $idx
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\PriceSettingDetail[] $price_setting_details
 * @property \App\Model\Entity\ProductOptionStock[] $product_option_stocks
 * @property \App\Model\Entity\ProductOptionValueList[] $product_option_value_lists
 */
class ProductOptionPrice extends Entity
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
        'product_id' => true,
        'sku' => true,
        'expired' => true,
        'price' => true,
        'idx' => true,
        'product' => true,
        'price_setting_details' => true,
        'product_option_stocks' => true,
        'product_option_value_lists' => true
    ];
}
