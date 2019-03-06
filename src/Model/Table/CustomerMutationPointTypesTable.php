<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerMutationPointTypes Model
 *
 * @property \App\Model\Table\CustomerMutationPointsTable|\Cake\ORM\Association\HasMany $CustomerMutationPoints
 *
 * @method \App\Model\Entity\CustomerMutationPointType get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPointType findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerMutationPointTypesTable extends Table
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

        $this->setTable('customer_mutation_point_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CustomerMutationPoints', [
            'foreignKey' => 'customer_mutation_point_type_id'
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
