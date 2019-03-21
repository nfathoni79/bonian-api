<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ProductStockMutations Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\BranchesTable|\Cake\ORM\Association\BelongsTo $Branches
 * @property \App\Model\Table\ProductOptionStocksTable|\Cake\ORM\Association\BelongsTo $ProductOptionStocks
 * @property \App\Model\Table\ProductStockMutationTypesTable|\Cake\ORM\Association\BelongsTo $ProductStockMutationTypes
 *
 * @method \App\Model\Entity\ProductStockMutation get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductStockMutation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductStockMutation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockMutation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockMutation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductStockMutationsTable extends Table
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

        $this->setTable('product_stock_mutations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductOptionStocks', [
            'foreignKey' => 'product_option_stock_id'
        ]);
        $this->belongsTo('ProductStockMutationTypes', [
            'foreignKey' => 'product_stock_mutation_type_id'
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->allowEmptyString('amount', false);

        $validator
            ->numeric('balance')
            ->requirePresence('balance', 'create')
            ->allowEmptyString('balance', false);

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
        $rules->add($rules->existsIn(['branch_id'], 'Branches'));
        $rules->add($rules->existsIn(['product_option_stock_id'], 'ProductOptionStocks'));
        $rules->add($rules->existsIn(['product_stock_mutation_type_id'], 'ProductStockMutationTypes'));

        return $rules;
    }

    public function saving($productOptionStockId, $transactionType, $amount, $description) {
//        $amount = bcmul(sprintf('%.8f', $amount),'1',0);

        $productStock = TableRegistry::get('ProductOptionStocks');
        $getStock = $productStock->find()
            ->where(['id' => $productOptionStockId])
            ->first();
        if($getStock){
            $stock = $getStock->get('stock');
            $balance = bcadd($stock,$amount);

            if($balance >= 0){

                $data = $this->newEntity();
                $data->product_id = $getStock->get('product_id');
                $data->branch_id = $getStock->get('branch_id');
                $data->product_option_stock_id = $getStock->get('id');
                $data->product_stock_mutation_type_id = $transactionType;
                $data->amount = $amount;
                $data->balance = $balance;
                $data->description = $description;
                if($this->save($data)){
                    $productStock->query()
                        ->update()
                        ->set(['stock' => $balance])
                        ->where(['id' => $getStock->get('id')])
                        ->execute();
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
