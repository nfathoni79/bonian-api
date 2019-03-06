<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * City Entity
 *
 * @property int $id
 * @property int $province_id
 * @property string $name
 * @property string $type
 * @property int $postal_code
 *
 * @property \App\Model\Entity\Province $province
 * @property \App\Model\Entity\Branch[] $branches
 * @property \App\Model\Entity\CustomerAddrese[] $customer_addreses
 * @property \App\Model\Entity\Subdistrict[] $subdistricts
 */
class City extends Entity
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
        'province_id' => true,
        'name' => true,
        'type' => true,
        'postal_code' => true,
        'province' => true,
        'branches' => true,
        'customer_addreses' => true,
        'subdistricts' => true
    ];
}
