<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Generations Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $ParentGenerations
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property |\Cake\ORM\Association\BelongsTo $Refferals
 * @property |\Cake\ORM\Association\HasMany $ChildGenerations
 *
 * @method \App\Model\Entity\Generation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Generation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Generation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Generation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Generation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Generation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Generation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Generation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class GenerationsTable extends Table
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

        $this->setTable('generations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('ParentGenerations', [
            'className' => 'Generations',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'className' => 'Customers'
        ]);
        $this->belongsTo('Refferals', [
            'foreignKey' => 'refferal_id',
            'className' => 'Customers'
        ]);
        $this->hasMany('ChildGenerations', [
            'className' => 'Generations',
            'foreignKey' => 'parent_id'
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
            ->integer('level')
            ->requirePresence('level', 'create')
            ->allowEmptyString('level', false);

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentGenerations'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['refferal_id'], 'Customers'));

        return $rules;
    }
}
