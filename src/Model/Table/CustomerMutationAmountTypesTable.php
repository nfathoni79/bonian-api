<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerMutationAmountTypes Model
 *
 * @property \App\Model\Table\CustomerMutationAmountsTable|\Cake\ORM\Association\HasMany $CustomerMutationAmounts
 *
 * @method \App\Model\Entity\CustomerMutationAmountType get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationAmountType findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerMutationAmountTypesTable extends Table
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

        $this->setTable('customer_mutation_amount_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CustomerMutationAmounts', [
            'foreignKey' => 'customer_mutation_amount_type_id'
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->allowEmptyString('type', false);

        return $validator;
    }
}
