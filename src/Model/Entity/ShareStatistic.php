<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShareStatistic Entity
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $media_type
 * @property int|null $customer_id
 * @property bool|null $clicked
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Customer $customer
 */
class ShareStatistic extends Entity
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
        'media_type' => true,
        'customer_id' => true,
        'clicked' => true,
        'created' => true,
        'modified' => true,
        'product' => true,
        'customer' => true
    ];
}
