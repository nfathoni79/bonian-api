<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductStockStatuses Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 *
 * @method \App\Model\Entity\ProductStockStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductStockStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductStockStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockStatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductStockStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductStockStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductStockStatusesTable extends Table
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

        $this->setTable('product_stock_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Products', [
            'foreignKey' => 'product_stock_status_id'
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
            ->maxLength('name', 20)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }
}
