<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductStockMutationTypes Model
 *
 * @property \App\Model\Table\ProductStockMutationsTable|\Cake\ORM\Association\HasMany $ProductStockMutations
 *
 * @method \App\Model\Entity\ProductStockMutationType get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductStockMutationType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductStockMutationType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutationType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockMutationType|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockMutationType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutationType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockMutationType findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductStockMutationTypesTable extends Table
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

        $this->setTable('product_stock_mutation_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ProductStockMutations', [
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
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }
}
