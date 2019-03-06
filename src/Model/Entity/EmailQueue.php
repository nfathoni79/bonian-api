<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailQueue Entity
 *
 * @property int $id
 * @property string $email
 * @property string|null $from_name
 * @property string|null $from_email
 * @property string $subject
 * @property string $template
 * @property string $layout
 * @property string|null $theme
 * @property string $format
 * @property string|null $text
 * @property string|null $html
 * @property string|null $headers
 * @property int $sent
 * @property int $locked
 * @property string|null $attachments
 * @property int $send_tries
 * @property string|null $error
 * @property \Cake\I18n\FrozenTime|null $send_at
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class EmailQueue extends Entity
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
        'from_name' => true,
        'from_email' => true,
        'subject' => true,
        'template' => true,
        'layout' => true,
        'theme' => true,
        'format' => true,
        'text' => true,
        'html' => true,
        'headers' => true,
        'sent' => true,
        'locked' => true,
        'attachments' => true,
        'send_tries' => true,
        'error' => true,
        'send_at' => true,
        'created' => true,
        'modified' => true
    ];
}
