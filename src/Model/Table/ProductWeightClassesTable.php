<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductWeightClasses Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 *
 * @method \App\Model\Entity\ProductWeightClass get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductWeightClass newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductWeightClass[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductWeightClass|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductWeightClass|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductWeightClass patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductWeightClass[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductWeightClass findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductWeightClassesTable extends Table
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

        $this->setTable('product_weight_classes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Products', [
            'foreignKey' => 'product_weight_class_id'
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

        $validator
            ->scalar('unit')
            ->maxLength('unit', 2)
            ->requirePresence('unit', 'create')
            ->allowEmptyString('unit', false);

        return $validator;
    }
}
