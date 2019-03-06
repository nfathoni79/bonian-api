<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Options Model
 *
 * @property \App\Model\Table\OptionValuesTable|\Cake\ORM\Association\HasMany $OptionValues
 * @property \App\Model\Table\ProductOptionValueListsTable|\Cake\ORM\Association\HasMany $ProductOptionValueLists
 *
 * @method \App\Model\Entity\Option get($primaryKey, $options = [])
 * @method \App\Model\Entity\Option newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Option[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Option|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Option|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Option patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Option[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Option findOrCreate($search, callable $callback = null, $options = [])
 */
class OptionsTable extends Table
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

        $this->setTable('options');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('OptionValues', [
            'foreignKey' => 'option_id'
        ]);
        $this->hasMany('ProductOptionValueLists', [
            'foreignKey' => 'option_id'
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
            ->maxLength('name', 150)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->allowEmptyString('sort_order', false);

        return $validator;
    }
}
