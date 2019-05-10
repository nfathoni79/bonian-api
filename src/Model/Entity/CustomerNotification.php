<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerNotification Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property int $customer_notification_type_id
 * @property string|null $message
 * @property string|null $model
 * @property int|null $foreign_key
 * @property string|null $controller
 * @property string|null $action
 * @property bool|null $is_read
 * @property string|null $template
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\CustomerNotificationType $customer_notification_type
 */
class CustomerNotification extends Entity
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
        'customer_notification_type_id' => true,
        'title' => true,
        'message' => true,
        'model' => true,
        'foreign_key' => true,
        'controller' => true,
        'action' => true,
        'is_read' => true,
        'template' => true,
        'created' => true,
        'modified' => true,
        'customer' => true,
        'customer_notification_type' => true
    ];
}
