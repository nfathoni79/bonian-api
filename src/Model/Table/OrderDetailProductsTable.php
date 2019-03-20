<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderDetailProducts Model
 *
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\BelongsTo $OrderDetails
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\OptionValuesTable|\Cake\ORM\Association\BelongsTo $OptionValues
 *
 * @method \App\Model\Entity\OrderDetailProduct get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrderDetailProduct newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrderDetailProduct[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetailProduct|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDetailProduct|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDetailProduct patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetailProduct[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetailProduct findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrderDetailProductsTable extends Table
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

        $this->setTable('order_detail_products');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('OrderDetails', [
            'foreignKey' => 'order_detail_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('OptionValues', [
            'foreignKey' => 'product_option_value_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductOptionPrices', [
            'foreignKey' => 'product_option_price_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductOptionStocks', [
            'foreignKey' => 'product_option_stock_id',
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
            ->integer('qty')
            ->requirePresence('qty', 'create')
            ->allowEmptyString('qty', false);

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->allowEmptyString('price', false);

        $validator
            ->numeric('total')
            ->requirePresence('total', 'create')
            ->allowEmptyString('total', false);

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
        $rules->add($rules->existsIn(['order_detail_id'], 'OrderDetails'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        //$rules->add($rules->existsIn(['product_option_value_id'], 'OptionValues'));
        $rules->add($rules->existsIn(['product_option_price_id'], 'ProductOptionPrices'));
        $rules->add($rules->existsIn(['product_option_stock_id'], 'ProductOptionStocks'));

        return $rules;
    }
}
