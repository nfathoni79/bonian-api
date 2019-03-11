<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * CustomerCartDetails Model
 *
 * @property \App\Model\Table\CustomerCartsTable|\Cake\ORM\Association\BelongsTo $CustomerCarts
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductOptionPricesTable|\Cake\ORM\Association\BelongsTo $ProductOptionPrices
 * @property \App\Model\Table\ProductOptionStocksTable|\Cake\ORM\Association\BelongsTo $ProductOptionStocks
 *
 * @method \App\Model\Entity\CustomerCartDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerCartDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerCartDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerCartDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerCartDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerCartDetailsTable extends Table
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

        $this->setTable('customer_cart_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CustomerCarts', [
            'foreignKey' => 'customer_cart_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
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
            ->requirePresence('qty')
            ->allowEmptyString('qty', false)
            ->add('qty',[
                'stock'=>[
                    'rule'=>'checkStock',
                    'provider'=>'table',
                    'message'=>'not enought stock'
                ]
            ]);



        $validator
            ->integer('product_id')
            ->requirePresence('product_id', 'create')
            ->allowEmptyString('product_id', false);

        $validator
            ->integer('product_option_price_id')
            ->requirePresence('product_option_price_id', 'create')
            ->allowEmptyString('product_option_price_id', false);

        $validator
            ->integer('product_option_stock_id')
            ->requirePresence('product_option_stock_id', 'create')
            ->allowEmptyString('product_option_stock_id', false);

//        $validator
//            ->requirePresence('product_option_value_list_code', 'create')
//            ->allowEmptyString('product_option_value_list_code', false);

//        $validator
//            ->scalar('product_option_value_list_code')
//            ->maxLength('product_option_value_list_code', 15)
//            ->requirePresence('product_option_value_list_code', 'create')
//            ->allowEmptyString('product_option_value_list_code', false);

        return $validator;
    }

    public function checkStock($value,$context){

        $productPrices = TableRegistry::get('ProductOptionPrices');
        $priceId = $context['data']['product_option_price_id'];
        $qty = $context['data']['qty'];
        $stockId = $context['data']['product_option_stock_id'];
        $prices = $productPrices->find()
            ->contain([
                'ProductOptionStocks',
            ])
            ->where(['ProductOptionPrices.id' => $priceId])
            ->first()->toArray();
        foreach($prices['product_option_stocks'] as $vals){
            if($vals['id'] == $stockId){
                $stockAvailable = $vals['stock'];
                if($qty > $stockAvailable){
                    return false;
                }else{
                    return true;
                }
                break;
            }
        }
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
        $rules->add($rules->existsIn(['customer_cart_id'], 'CustomerCarts'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['product_option_price_id'], 'ProductOptionPrices'));
        $rules->add($rules->existsIn(['product_option_stock_id'], 'ProductOptionStocks'));

        return $rules;
    }
}
