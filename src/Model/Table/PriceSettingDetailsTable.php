<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PriceSettingDetails Model
 *
 * @property \App\Model\Table\PriceSettingsTable|\Cake\ORM\Association\BelongsTo $PriceSettings
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductOptionPricesTable|\Cake\ORM\Association\BelongsTo $ProductOptionPrices
 *
 * @method \App\Model\Entity\PriceSettingDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\PriceSettingDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PriceSettingDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PriceSettingDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceSettingDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PriceSettingDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PriceSettingDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PriceSettingDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class PriceSettingDetailsTable extends Table
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

        $this->setTable('price_setting_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('PriceSettings', [
            'foreignKey' => 'price_setting_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('ProductOptionPrices', [
            'foreignKey' => 'product_option_price_id'
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
            ->scalar('sku')
            ->maxLength('sku', 50)
            ->requirePresence('sku', 'create')
            ->allowEmptyString('sku', false);

        $validator
            ->scalar('type')
            ->maxLength('type', 10)
            ->requirePresence('type', 'create')
            ->allowEmptyString('type', false);

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

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
        $rules->add($rules->existsIn(['price_setting_id'], 'PriceSettings'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['product_option_price_id'], 'ProductOptionPrices'));

        return $rules;
    }
}
