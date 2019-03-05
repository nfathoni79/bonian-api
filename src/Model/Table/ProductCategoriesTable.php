<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductCategories Model
 *
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\BelongsTo $ParentProductCategories
 * @property \App\Model\Table\AttributesTable|\Cake\ORM\Association\HasMany $Attributes
 * @property \App\Model\Table\BrandsTable|\Cake\ORM\Association\HasMany $Brands
 * @property \App\Model\Table\CustomerLogBrowsingsTable|\Cake\ORM\Association\HasMany $CustomerLogBrowsings
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\HasMany $ChildProductCategories
 * @property \App\Model\Table\ProductToCategoriesTable|\Cake\ORM\Association\HasMany $ProductToCategories
 *
 * @method \App\Model\Entity\ProductCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductCategory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class ProductCategoriesTable extends Table
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

        $this->setTable('product_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentProductCategories', [
            'className' => 'ProductCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Attributes', [
            'foreignKey' => 'product_category_id'
        ]);
        $this->hasMany('Brands', [
            'foreignKey' => 'product_category_id'
        ]);
        $this->hasMany('CustomerLogBrowsings', [
            'foreignKey' => 'product_category_id'
        ]);
        $this->hasMany('ChildProductCategories', [
            'className' => 'ProductCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ProductToCategories', [
            'foreignKey' => 'product_category_id'
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

        $validator
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->requirePresence('slug', 'create')
            ->allowEmptyString('slug', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->requirePresence('path', 'create')
            ->allowEmptyString('path', false);

        $validator
            ->integer('counter_view')
            ->allowEmptyString('counter_view');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentProductCategories'));

        return $rules;
    }
}
