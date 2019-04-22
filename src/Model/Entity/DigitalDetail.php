<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DigitalDetail Entity
 *
 * @property int $id
 * @property int $digital_id
 * @property string $code
 * @property string $name
 * @property float|null $denom
 * @property string $operator
 * @property float $price
 * @property int|null $status
 *
 * @property \App\Model\Entity\Digital $digital
 */
class DigitalDetail extends Entity
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
        'digital_id' => true,
        'code' => true,
        'name' => true,
        'denom' => true,
        'operator' => true,
        'price' => true,
        'status' => true,
        'digital' => true
    ];
}
