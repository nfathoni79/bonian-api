<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerBuyGroups Model
 *
 * @property \App\Model\Table\ProductGroupsTable|\Cake\ORM\Association\BelongsTo $ProductGroups
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\CustomerBuyGroupDetailsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroupDetails
 *
 * @method \App\Model\Entity\CustomerBuyGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerBuyGroup findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerBuyGroupsTable extends Table
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

        $this->setTable('customer_buy_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductGroups', [
            'foreignKey' => 'product_group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CustomerBuyGroupDetails', [
            'foreignKey' => 'customer_buy_group_id'
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
            ->scalar('name')
            ->maxLength('name', 5)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

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
        $rules->add($rules->existsIn(['product_group_id'], 'ProductGroups'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
