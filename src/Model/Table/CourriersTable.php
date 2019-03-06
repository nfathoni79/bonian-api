<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Courriers Model
 *
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\HasMany $OrderDetails
 * @property \App\Model\Table\ProductToCourriersTable|\Cake\ORM\Association\HasMany $ProductToCourriers
 *
 * @method \App\Model\Entity\Courrier get($primaryKey, $options = [])
 * @method \App\Model\Entity\Courrier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Courrier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Courrier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Courrier|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Courrier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Courrier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Courrier findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CourriersTable extends Table
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

        $this->setTable('courriers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('OrderDetails', [
            'foreignKey' => 'courrier_id'
        ]);
        $this->hasMany('ProductToCourriers', [
            'foreignKey' => 'courrier_id'
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
            ->maxLength('name', 15)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('code')
            ->maxLength('code', 5)
            ->requirePresence('code', 'create')
            ->allowEmptyString('code', false);

        return $validator;
    }
}
