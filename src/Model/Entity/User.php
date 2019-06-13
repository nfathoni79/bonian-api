<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property int $group_id
 * @property int $user_status_id
 * @property int|null $branch_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\UserStatus $user_status
 * @property \App\Model\Entity\Branch $branch
 * @property \App\Model\Entity\ChatDetail[] $chat_details
 * @property \App\Model\Entity\PriceSetting[] $price_settings
 */
class User extends Entity
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
        'email' => true,
        'username' => true,
        'password' => true,
        'first_name' => true,
        'last_name' => true,
        'group_id' => true,
        'user_status_id' => true,
        'branch_id' => true,
        'created' => true,
        'modified' => true,
        'group' => true,
        'user_status' => true,
        'branch' => true,
        'chat_details' => true,
        'price_settings' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
