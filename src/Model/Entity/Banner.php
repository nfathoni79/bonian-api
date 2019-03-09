<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Banner Entity
 *
 * @property int $id
 * @property int|null $product_category_id
 * @property string $position
 * @property string|null $name
 * @property string|null $dir
 * @property int|null $size
 * @property string|null $type
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\ProductCategory $product_category
 */
class Banner extends Entity
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
        'product_category_id' => true,
        'position' => true,
        'url' => true,
        'name' => true,
        'dir' => true,
        'size' => true,
        'type' => true,
        'status' => true,
        'created' => true,
        'product_category' => true
    ];
}
