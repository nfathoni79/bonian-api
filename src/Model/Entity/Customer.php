<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Customer Entity
 *
 * @property int $id
 * @property string $reffcode
 * @property int $refferal_customer_id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property \Cake\I18n\FrozenDate $dob
 * @property int|null $customer_group_id
 * @property int|null $customer_status_id
 * @property int $is_verified
 * @property string|null $activation
 * @property string $platforrm
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\RefferalCustomer $refferal_customer
 * @property \App\Model\Entity\CustomerGroup $customer_group
 * @property \App\Model\Entity\CustomerStatus $customer_status
 * @property \App\Model\Entity\ChatDetail[] $chat_details
 * @property \App\Model\Entity\CustomerAddrese[] $customer_addreses
 * @property \App\Model\Entity\CustomerBalance[] $customer_balances
 * @property \App\Model\Entity\CustomerBuyGroupDetail[] $customer_buy_group_details
 * @property \App\Model\Entity\CustomerBuyGroup[] $customer_buy_groups
 * @property \App\Model\Entity\CustomerLogBrowsing[] $customer_log_browsings
 * @property \App\Model\Entity\CustomerMutationAmount[] $customer_mutation_amounts
 * @property \App\Model\Entity\CustomerMutationPoint[] $customer_mutation_points
 * @property \App\Model\Entity\CustomerToken[] $customer_tokens
 * @property \App\Model\Entity\CustomerVirtualAccount[] $customer_virtual_account
 * @property \App\Model\Entity\Generation[] $generations
 * @property \App\Model\Entity\Order[] $orders
 */
class Customer extends Entity
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
        'reffcode' => true,
        'refferal_customer_id' => true,
        'email' => true,
        'username' => true,
        'password' => true,
        'first_name' => true,
        'last_name' => true,
        'phone' => true,
        'dob' => true,
        'customer_group_id' => true,
        'customer_status_id' => true,
        'is_verified' => true,
        'activation' => true,
        'platforrm' => true,
        'created' => true,
        'modified' => true,
        'refferal_customer' => true,
        'customer_group' => true,
        'customer_status' => true,
        'chat_details' => true,
        'customer_addreses' => true,
        'customer_balances' => true,
        'customer_buy_group_details' => true,
        'customer_buy_groups' => true,
        'customer_log_browsings' => true,
        'customer_mutation_amounts' => true,
        'customer_mutation_points' => true,
        'customer_tokens' => true,
        'customer_virtual_account' => true,
        'generations' => true,
        'orders' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
