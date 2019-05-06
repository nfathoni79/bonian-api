<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerDigitalInquiry Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $customer_number
 * @property string|null $code
 * @property bool|null $status
 * @property string|null $raw_request
 * @property string|null $raw_response
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerDigitalInquiry extends Entity
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
        'customer_number' => true,
        'code' => true,
        'status' => true,
        'raw_request' => true,
        'raw_response' => true,
        'created' => true,
        'modified' => true,
        'customer' => true
    ];
}
