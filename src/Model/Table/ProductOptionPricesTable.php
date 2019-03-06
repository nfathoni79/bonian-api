<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductOptionPrices Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\PriceSettingDetailsTable|\Cake\ORM\Association\HasMany $PriceSettingDetails
 * @property \App\Model\Table\ProductOptionStocksTable|\Cake\ORM\Association\HasMany $ProductOptionStocks
 * @property \App\Model\Table\ProductOptionValueListsTable|\Cake\ORM\Association\HasMany $ProductOptionValueLists
 *
 * @method \App\Model\Entity\ProductOptionPrice get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductOptionPrice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductOptionPrice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionPrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductOptionPrice|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductOptionPrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionPrice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductOptionPrice findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductOptionPricesTable extends Table
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

        $this->setTable('product_option_prices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PriceSettingDetails', [
            'foreignKey' => 'product_option_price_id'
        ]);
        $this->hasMany('ProductOptionStocks', [
            'foreignKey' => 'product_option_price_id'
        ]);
        $this->hasMany('ProductOptionValueLists', [
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
            ->date('expired')
            ->allowEmptyDate('expired');

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->integer('idx')
            ->allowEmptyString('idx');

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
