<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuthCode Entity
 *
 * @property int $id
 * @property string $phone
 * @property string $name
 * @property string|null $code
 * @property int|null $used
 * @property \Cake\I18n\FrozenTime|null $expired
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class AuthCode extends Entity
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
        'phone' => true,
        'name' => true,
        'code' => true,
        'used' => true,
        'expired' => true,
        'created' => true,
        'modified' => true
    ];
}
