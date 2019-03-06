<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 *
 * @property \App\Model\Table\RefferalCustomersTable|\Cake\ORM\Association\BelongsTo $RefferalCustomers
 * @property \App\Model\Table\CustomerGroupsTable|\Cake\ORM\Association\BelongsTo $CustomerGroups
 * @property \App\Model\Table\CustomerStatusesTable|\Cake\ORM\Association\BelongsTo $CustomerStatuses
 * @property \App\Model\Table\ChatDetailsTable|\Cake\ORM\Association\HasMany $ChatDetails
 * @property \App\Model\Table\CustomerAddresesTable|\Cake\ORM\Association\HasMany $CustomerAddreses
 * @property \App\Model\Table\CustomerBalancesTable|\Cake\ORM\Association\HasMany $CustomerBalances
 * @property \App\Model\Table\CustomerBuyGroupDetailsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroupDetails
 * @property \App\Model\Table\CustomerBuyGroupsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroups
 * @property \App\Model\Table\CustomerLogBrowsingsTable|\Cake\ORM\Association\HasMany $CustomerLogBrowsings
 * @property \App\Model\Table\CustomerMutationAmountsTable|\Cake\ORM\Association\HasMany $CustomerMutationAmounts
 * @property \App\Model\Table\CustomerMutationPointsTable|\Cake\ORM\Association\HasMany $CustomerMutationPoints
 * @property \App\Model\Table\CustomerTokensTable|\Cake\ORM\Association\HasMany $CustomerTokens
 * @property \App\Model\Table\CustomerVirtualAccountTable|\Cake\ORM\Association\HasMany $CustomerVirtualAccount
 * @property \App\Model\Table\GenerationsTable|\Cake\ORM\Association\HasMany $Generations
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\Customer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Customer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Customer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Customer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Customer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Customer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomersTable extends Table
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

        $this->setTable('customers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('RefferalCustomers', [
            'foreignKey' => 'refferal_customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CustomerGroups', [
            'foreignKey' => 'customer_group_id'
        ]);
        $this->belongsTo('CustomerStatuses', [
            'foreignKey' => 'customer_status_id'
        ]);
        $this->hasMany('ChatDetails', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerAddreses', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBalances', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBuyGroupDetails', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBuyGroups', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerLogBrowsings', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerMutationAmounts', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerMutationPoints', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerTokens', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerVirtualAccount', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Generations', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Orders', [
            'foreignKey' => 'customer_id'
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
            ->scalar('reffcode')
            ->maxLength('reffcode', 10)
            ->requirePresence('reffcode', 'create')
            ->allowEmptyString('reffcode', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('username')
            ->maxLength('username', 30)
            ->requirePresence('username', 'create')
            ->allowEmptyString('username', false);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->allowEmptyString('password', false);

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 40)
            ->requirePresence('first_name', 'create')
            ->allowEmptyString('first_name', false);

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 30)
            ->requirePresence('last_name', 'create')
            ->allowEmptyString('last_name', false);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 15)
            ->requirePresence('phone', 'create')
            ->allowEmptyString('phone', false);

        $validator
            ->date('dob')
            ->requirePresence('dob', 'create')
            ->allowEmptyDate('dob', false);

        $validator
            ->integer('is_verified')
            ->requirePresence('is_verified', 'create')
            ->allowEmptyString('is_verified', false);

        $validator
            ->scalar('activation')
            ->maxLength('activation', 255)
            ->allowEmptyString('activation');

        $validator
            ->scalar('platforrm')
            ->maxLength('platforrm', 15)
            ->requirePresence('platforrm', 'create')
            ->allowEmptyString('platforrm', false);

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['refferal_customer_id'], 'RefferalCustomers'));
        $rules->add($rules->existsIn(['customer_group_id'], 'CustomerGroups'));
        $rules->add($rules->existsIn(['customer_status_id'], 'CustomerStatuses'));

        return $rules;
    }
}
