<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductOptionValueLists Model
 *
 * @property \App\Model\Table\ProductOptionPricesTable|\Cake\ORM\Association\BelongsTo $ProductOptionPrices
 * @property \App\Model\Table\OptionsTable|\Cake\ORM\Association\BelongsTo $Options
 * @property \App\Model\Table\OptionValuesTable|\Cake\ORM\Association\BelongsTo $OptionValues
 *
 * @method \App\Model\Entity\ProductOptionValueList get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductOptionValueList newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductOptionValueList[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionValueList|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductOptionValueList|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductOptionValueList patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionValueList[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionValueList findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductOptionValueListsTable extends Table
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

        $this->setTable('product_option_value_lists');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductOptionPrices', [
            'foreignKey' => 'product_option_price_id'
        ]);
        $this->belongsTo('Options', [
            'foreignKey' => 'option_id'
        ]);
        $this->belongsTo('OptionValues', [
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
        $rules->add($rules->existsIn(['product_option_price_id'], 'ProductOptionPrices'));
        $rules->add($rules->existsIn(['option_id'], 'Options'));
        $rules->add($rules->existsIn(['option_value_id'], 'OptionValues'));

        return $rules;
    }
}
