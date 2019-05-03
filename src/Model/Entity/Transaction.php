<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property int|null $order_id
 * @property string $transaction_id
 * @property \Cake\I18n\FrozenTime|null $transaction_time
 * @property string|null $transaction_code
 * @property string|null $transaction_status
 * @property string|null $fraud_status
 * @property float|null $gross_amount
 * @property string|null $currency
 * @property string|null $payment_type
 * @property string|null $bank
 * @property string|null $va_number
 * @property string|null $masked_card
 * @property string|null $card_type
 * @property string|null $approval_code
 * @property string|null $raw_response
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Order $order
 */
class Transaction extends Entity
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
        'order_id' => true,
        'transaction_id' => true,
        'transaction_time' => true,
        'transaction_code' => true,
        'transaction_status' => true,
        'fraud_status' => true,
        'gross_amount' => true,
        'currency' => true,
        'payment_type' => true,
        'bank' => true,
        'va_number' => true,
        'masked_card' => true,
        'card_type' => true,
        'approval_code' => true,
        'raw_response' => true,
        'created' => true,
        'modified' => true,
        'order' => true
    ];
}
