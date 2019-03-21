<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\BelongsTo $Orders
 *
 * @method \App\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transaction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransactionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->dateTime('transaction_time')
            ->allowEmptyDateTime('transaction_time');

        $validator
            ->scalar('transaction_code')
            ->maxLength('transaction_code', 50)
            ->allowEmptyString('transaction_code');

        $validator
            ->scalar('transaction_status')
            ->maxLength('transaction_status', 20)
            ->allowEmptyString('transaction_status');

        $validator
            ->scalar('fraud_status')
            ->maxLength('fraud_status', 20)
            ->allowEmptyString('fraud_status');

        $validator
            ->numeric('gross_amount')
            ->allowEmptyString('gross_amount');

        $validator
            ->scalar('currency')
            ->maxLength('currency', 3)
            ->allowEmptyString('currency');

        $validator
            ->scalar('payment_type')
            ->maxLength('payment_type', 20)
            ->allowEmptyString('payment_type');

        $validator
            ->scalar('bank')
            ->maxLength('bank', 10)
            ->allowEmptyString('bank');

        $validator
            ->scalar('va_number')
            ->maxLength('va_number', 25)
            ->allowEmptyString('va_number');

        $validator
            ->scalar('masked_card')
            ->maxLength('masked_card', 25)
            ->allowEmptyString('masked_card');

        $validator
            ->scalar('card_type')
            ->maxLength('card_type', 15)
            ->allowEmptyString('card_type');

        $validator
            ->scalar('approval_code')
            ->maxLength('approval_code', 20)
            ->allowEmptyString('approval_code');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['order_id'], 'Orders'));

        return $rules;
    }
}
