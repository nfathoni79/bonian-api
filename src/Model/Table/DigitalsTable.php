<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Digitals Model
 *
 * @property \App\Model\Table\DigitalDetailsTable|\Cake\ORM\Association\HasMany $DigitalDetails
 *
 * @method \App\Model\Entity\Digital get($primaryKey, $options = [])
 * @method \App\Model\Entity\Digital newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Digital[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Digital|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Digital|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Digital patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Digital[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Digital findOrCreate($search, callable $callback = null, $options = [])
 */
class DigitalsTable extends Table
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

        $this->setTable('digitals');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('DigitalDetails', [
            'foreignKey' => 'digital_id'
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
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        return $validator;
    }
}
