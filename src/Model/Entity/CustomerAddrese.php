<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerAddrese Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $province_id
 * @property int|null $city_id
 * @property int|null $subdistrict_id
 * @property int|null $is_primary
 * @property string $title
 * @property string $recipient_name
 * @property string $recipient_phone
 * @property float $latitude
 * @property float $longitude
 * @property int|null $postal_code
 * @property string $address
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Province $province
 * @property \App\Model\Entity\City $city
 * @property \App\Model\Entity\Subdistrict $subdistrict
 */
class CustomerAddrese extends Entity
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
        'customer_id' => true,
        'province_id' => true,
        'city_id' => true,
        'subdistrict_id' => true,
        'is_primary' => true,
        'title' => true,
        'recipient_name' => true,
        'recipient_phone' => true,
        'latitude' => true,
        'longitude' => true,
        'postal_code' => true,
        'address' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'province' => true,
        'city' => true,
        'subdistrict' => true
    ];
}
