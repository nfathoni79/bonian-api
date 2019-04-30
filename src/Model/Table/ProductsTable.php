<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\ProductStockStatusesTable|\Cake\ORM\Association\BelongsTo $ProductStockStatuses
 * @property \App\Model\Table\ProductWeightClassesTable|\Cake\ORM\Association\BelongsTo $ProductWeightClasses
 * @property \App\Model\Table\ProductWarrantiesTable|\Cake\ORM\Association\BelongsTo $ProductWarranties
 * @property \App\Model\Table\BrandsTable|\Cake\ORM\Association\BelongsTo $Brands
 * @property \App\Model\Table\ProductStatusesTable|\Cake\ORM\Association\BelongsTo $ProductStatuses
 * @property \App\Model\Table\CustomerLogBrowsingsTable|\Cake\ORM\Association\HasMany $CustomerLogBrowsings
 * @property \App\Model\Table\OrderDetailProductsTable|\Cake\ORM\Association\HasMany $OrderDetailProducts
 * @property \App\Model\Table\PriceSettingDetailsTable|\Cake\ORM\Association\HasMany $PriceSettingDetails
 * @property \App\Model\Table\ProductAttributesTable|\Cake\ORM\Association\HasMany $ProductAttributes
 * @property \App\Model\Table\ProductBranchesTable|\Cake\ORM\Association\HasMany $ProductBranches
 * @property \App\Model\Table\ProductDealDetailsTable|\Cake\ORM\Association\HasMany $ProductDealDetails
 * @property \App\Model\Table\ProductDiscountsTable|\Cake\ORM\Association\HasMany $ProductDiscounts
 * @property \App\Model\Table\ProductGroupDetailsTable|\Cake\ORM\Association\HasMany $ProductGroupDetails
 * @property \App\Model\Table\ProductImagesTable|\Cake\ORM\Association\HasMany $ProductImages
 * @property \App\Model\Table\ProductMetaTagsTable|\Cake\ORM\Association\HasMany $ProductMetaTags
 * @property \App\Model\Table\ProductOptionPricesTable|\Cake\ORM\Association\HasMany $ProductOptionPrices
 * @property \App\Model\Table\ProductOptionStocksTable|\Cake\ORM\Association\HasMany $ProductOptionStocks
 * @property \App\Model\Table\ProductPromotionsTable|\Cake\ORM\Association\HasMany $ProductPromotions
 * @property \App\Model\Table\ProductStockMutationsTable|\Cake\ORM\Association\HasMany $ProductStockMutations
 * @property \App\Model\Table\ProductTagsTable|\Cake\ORM\Association\HasMany $ProductTags
 * @property \App\Model\Table\ProductToCategoriesTable|\Cake\ORM\Association\HasMany $ProductToCategories
 * @property \App\Model\Table\ProductToCourriersTable|\Cake\ORM\Association\HasMany $ProductToCourriers
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ProductStockStatuses', [
            'foreignKey' => 'product_stock_status_id'
        ]);
        $this->belongsTo('ProductWeightClasses', [
            'foreignKey' => 'product_weight_class_id'
        ]);
        $this->belongsTo('ProductWarranties', [
            'foreignKey' => 'product_warranty_id'
        ]);
        $this->belongsTo('Brands', [
            'foreignKey' => 'brand_id'
        ]);
        $this->belongsTo('ProductStatuses', [
            'foreignKey' => 'product_status_id'
        ]);
        $this->hasMany('CustomerLogBrowsings', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('OrderDetailProducts', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('PriceSettingDetails', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductAttributes', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductBranches', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductDealDetails', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductDiscounts', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductGroupDetails', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductImages', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductMetaTags', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductOptionPrices', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductOptionStocks', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductPromotions', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductStockMutations', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductTags', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductToCategories', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductToCourriers', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductCoupons', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductDiscussions', [
            'foreignKey' => 'product_id'
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->allowEmptyString('slug');

        $validator
            ->scalar('model')
            ->maxLength('model', 100)
            ->allowEmptyString('model');

        $validator
            ->scalar('code')
            ->maxLength('code', 50)
            ->allowEmptyString('code');

        $validator
            ->scalar('sku')
            ->maxLength('sku', 25)
            ->allowEmptyString('sku');

        $validator
            ->scalar('barcode')
            ->maxLength('barcode', 50)
            ->requirePresence('barcode', 'create')
            ->allowEmptyString('barcode', false);

        $validator
            ->scalar('supplier_code')
            ->maxLength('supplier_code', 50)
            ->requirePresence('supplier_code', 'create')
            ->allowEmptyString('supplier_code', false);

        $validator
            ->integer('qty')
            ->requirePresence('qty', 'create')
            ->allowEmptyString('qty', false);

        $validator
            ->integer('shipping')
            ->allowEmptyString('shipping');

        $validator
            ->numeric('price')
            ->allowEmptyString('price');

        $validator
            ->numeric('price_sale')
            ->requirePresence('price_sale', 'create')
            ->allowEmptyString('price_sale', false);

        $validator
            ->numeric('weight')
            ->allowEmptyString('weight');

        $validator
            ->scalar('highlight')
            ->allowEmptyString('highlight');

        $validator
            ->scalar('condition')
            ->allowEmptyString('condition');

        $validator
            ->scalar('profile')
            ->allowEmptyFile('profile');

        $validator
            ->integer('view')
            ->requirePresence('view', 'create')
            ->allowEmptyString('view', false);

        $validator
            ->integer('point')
            ->requirePresence('point', 'create')
            ->allowEmptyString('point', false);

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
        $rules->add($rules->existsIn(['product_stock_status_id'], 'ProductStockStatuses'));
        $rules->add($rules->existsIn(['product_weight_class_id'], 'ProductWeightClasses'));
        $rules->add($rules->existsIn(['product_warranty_id'], 'ProductWarranties'));
        $rules->add($rules->existsIn(['brand_id'], 'Brands'));
        $rules->add($rules->existsIn(['product_status_id'], 'ProductStatuses'));

        return $rules;
    }
}
