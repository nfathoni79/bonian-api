<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Security;
use Cake\Core\Configure;

/**
 * CustomerCard Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property bool|null $is_primary
 * @property string|null $masked_card
 * @property string|null $type
 * @property string|null $token
 * @property \Cake\I18n\FrozenTime|null $expired_at
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer $customer
 */
class CustomerCard extends Entity
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
        'is_primary' => true,
        'masked_card' => true,
        'type' => true,
        'token' => true,
        'expired_at' => true,
        'created' => true,
        'customer' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        //'token'
    ];

    protected function _setToken($token)
    {
        return base64_encode(Security::encrypt($token, Configure::read('Encrypt.salt') . $this->get('customer_id')));
    }

    protected function _getToken($token)
    {
		if ($this->isDirty('token')) {
			return $token;
		}
        return Security::decrypt(base64_decode($token), Configure::read('Encrypt.salt') . $this->get('customer_id'));
    }
}
