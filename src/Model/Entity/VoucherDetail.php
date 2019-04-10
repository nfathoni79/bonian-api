<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VoucherDetail Entity
 *
 * @property int $id
 * @property int|null $voucher_id
 * @property int|null $product_category_id
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Voucher $voucher
 * @property \App\Model\Entity\ProductCategory $product_category
 */
class VoucherDetail extends Entity
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
        'voucher_id' => true,
        'product_category_id' => true,
        'created' => true,
        'voucher' => true,
        'product_category' => true
    ];
}
