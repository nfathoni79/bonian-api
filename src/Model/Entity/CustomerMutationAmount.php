<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerMutationAmount Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $customer_mutation_amount_type_id
 * @property string $description
 * @property float $amount
 * @property float $balance
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\CustomerMutationAmountType $customer_mutation_amount_type
 */
class CustomerMutationAmount extends Entity
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
        'customer_mutation_amount_type_id' => true,
        'description' => true,
        'amount' => true,
        'balance' => true,
        'created' => true,
        'customer' => true,
        'customer_mutation_amount_type' => true
    ];
}
