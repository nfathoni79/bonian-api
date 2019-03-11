<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $model
 * @property string|null $code
 * @property string|null $sku
 * @property string $barcode
 * @property string $supplier_code
 * @property int $qty
 * @property int|null $product_stock_status_id
 * @property int|null $shipping
 * @property float|null $price
 * @property float $price_sale
 * @property float|null $weight
 * @property int|null $product_weight_class_id
 * @property int|null $product_warranty_id
 * @property int|null $brand_id
 * @property int|null $product_status_id
 * @property string|null $highlight
 * @property string|null $condition
 * @property string|null $profile
 * @property int $view
 * @property int $point
 * @property float $rating
 * @property int $rating_count
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\ProductStockStatus $product_stock_status
 * @property \App\Model\Entity\ProductWeightClass $product_weight_class
 * @property \App\Model\Entity\ProductWarranty $product_warranty
 * @property \App\Model\Entity\Brand $brand
 * @property \App\Model\Entity\ProductStatus $product_status
 * @property \App\Model\Entity\CustomerLogBrowsing[] $customer_log_browsings
 * @property \App\Model\Entity\OrderDetailProduct[] $order_detail_products
 * @property \App\Model\Entity\PriceSettingDetail[] $price_setting_details
 * @property \App\Model\Entity\ProductAttribute[] $product_attributes
 * @property \App\Model\Entity\ProductBranch[] $product_branches
 * @property \App\Model\Entity\ProductDealDetail[] $product_deal_details
 * @property \App\Model\Entity\ProductDiscount[] $product_discounts
 * @property \App\Model\Entity\ProductGroupDetail[] $product_group_details
 * @property \App\Model\Entity\ProductImage[] $product_images
 * @property \App\Model\Entity\ProductMetaTag[] $product_meta_tags
 * @property \App\Model\Entity\ProductOptionPrice[] $product_option_prices
 * @property \App\Model\Entity\ProductOptionStock[] $product_option_stocks
 * @property \App\Model\Entity\ProductPromotion[] $product_promotions
 * @property \App\Model\Entity\ProductStockMutation[] $product_stock_mutations
 * @property \App\Model\Entity\ProductTag[] $product_tags
 * @property \App\Model\Entity\ProductToCategory[] $product_to_categories
 * @property \App\Model\Entity\ProductToCourrier[] $product_to_courriers
 */
class Product extends Entity
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
        'title' => true,
        'slug' => true,
        'model' => true,
        'code' => true,
        'sku' => true,
        'barcode' => true,
        'supplier_code' => true,
        'qty' => true,
        'product_stock_status_id' => true,
        'shipping' => true,
        'price' => true,
        'price_sale' => true,
        'weight' => true,
        'product_weight_class_id' => true,
        'product_warranty_id' => true,
        'brand_id' => true,
        'product_status_id' => true,
        'highlight' => true,
        'condition' => true,
        'profile' => true,
        'view' => true,
        'point' => true,
        'rating' => true,
        'rating_count' => true,
        'created' => true,
        'modified' => true,
        'product_stock_status' => true,
        'product_weight_class' => true,
        'product_warranty' => true,
        'brand' => true,
        'product_status' => true,
        'customer_log_browsings' => true,
        'order_detail_products' => true,
        'price_setting_details' => true,
        'product_attributes' => true,
        'product_branches' => true,
        'product_deal_details' => true,
        'product_discounts' => true,
        'product_group_details' => true,
        'product_images' => true,
        'product_meta_tags' => true,
        'product_option_prices' => true,
        'product_option_stocks' => true,
        'product_promotions' => true,
        'product_stock_mutations' => true,
        'product_tags' => true,
        'product_to_categories' => true,
        'product_to_courriers' => true
    ];
}
