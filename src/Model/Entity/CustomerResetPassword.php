<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerResetPassword Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $request_name
 * @property int|null $request_type
 * @property string|null $otp
 * @property string|null $session_id
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerResetPassword extends Entity
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
        'request_name' => true,
        'request_type' => true,
        'otp' => true,
        'session_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
    ];
}
