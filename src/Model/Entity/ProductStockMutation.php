<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductStockMutation Entity
 *
 * @property int $id
 * @property int $product_id
 * @property int $branch_id
 * @property int|null $product_option_stock_id
 * @property int|null $product_stock_mutation_type_id
 * @property string $description
 * @property float $amount
 * @property float $balance
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Branch $branch
 * @property \App\Model\Entity\ProductOptionStock $product_option_stock
 * @property \App\Model\Entity\ProductStockMutationType $product_stock_mutation_type
 */
class ProductStockMutation extends Entity
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
        'branch_id' => true,
        'product_option_stock_id' => true,
        'product_stock_mutation_type_id' => true,
        'description' => true,
        'amount' => true,
        'balance' => true,
        'created' => true,
        'product' => true,
        'branch' => true,
        'product_option_stock' => true,
        'product_stock_mutation_type' => true
    ];
}
