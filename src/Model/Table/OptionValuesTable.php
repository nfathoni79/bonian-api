<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OptionValues Model
 *
 * @property \App\Model\Table\OptionsTable|\Cake\ORM\Association\BelongsTo $Options
 * @property \App\Model\Table\ProductOptionValueListsTable|\Cake\ORM\Association\HasMany $ProductOptionValueLists
 *
 * @method \App\Model\Entity\OptionValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\OptionValue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OptionValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OptionValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OptionValue|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OptionValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OptionValue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OptionValue findOrCreate($search, callable $callback = null, $options = [])
 */
class OptionValuesTable extends Table
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

        $this->setTable('option_values');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Options', [
            'foreignKey' => 'option_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ProductOptionValueLists', [
            'foreignKey' => 'option_value_id'
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
        $rules->add($rules->existsIn(['option_id'], 'Options'));

        return $rules;
    }
}
