<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DigitalDetails Model
 *
 * @property \App\Model\Table\DigitalsTable|\Cake\ORM\Association\BelongsTo $Digitals
 *
 * @method \App\Model\Entity\DigitalDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\DigitalDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DigitalDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DigitalDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DigitalDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DigitalDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DigitalDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DigitalDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class DigitalDetailsTable extends Table
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

        $this->setTable('digital_details');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Digitals', [
            'foreignKey' => 'digital_id',
            'joinType' => 'INNER'
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
            ->scalar('code')
            ->maxLength('code', 15)
            ->requirePresence('code', 'create')
            ->allowEmptyString('code', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->numeric('denom')
            ->allowEmptyString('denom');

        $validator
            ->scalar('operator')
            ->maxLength('operator', 15)
            ->requirePresence('operator', 'create')
            ->allowEmptyString('operator', false);

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->integer('status')
            ->allowEmptyString('status');

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
        $rules->add($rules->existsIn(['digital_id'], 'Digitals'));

        return $rules;
    }
}
