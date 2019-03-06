<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerPointRates Model
 *
 * @method \App\Model\Entity\CustomerPointRate get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerPointRate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerPointRate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerPointRate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerPointRate|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerPointRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerPointRate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerPointRate findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerPointRatesTable extends Table
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

        $this->setTable('customer_point_rates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('point')
            ->requirePresence('point', 'create')
            ->allowEmptyString('point', false);

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->allowEmptyString('value', false);

        return $validator;
    }
}
