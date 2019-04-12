<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IpLocation Entity
 *
 * @property int $id
 * @property string|null $ip
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country_name
 * @property string|null $country_code
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $asn
 * @property string|null $organisation
 */
class IpLocation extends Entity
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
        'ip' => true,
        'city' => true,
        'region' => true,
        'country_name' => true,
        'country_code' => true,
        'latitude' => true,
        'longitude' => true,
        'asn' => true,
        'organisation' => true
    ];
}
