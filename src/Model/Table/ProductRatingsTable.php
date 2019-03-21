<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductRatings Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\ProductRating get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductRating newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductRating[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductRating|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductRating|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductRating patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductRating[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductRating findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductRatingsTable extends Table
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

        $this->setTable('product_ratings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id'
        ]);
        $this->belongsTo('OrderDetailProducts', [
            'foreignKey' => 'order_detail_product_id'
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
            ->integer('rating')
            ->allowEmptyString('rating')
            ->inList('rating', [1,2,3,4,5], 'Rating harus skala 1 - 5');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['order_detail_product_id'], 'OrderDetailProducts'));

//        $rules->add($rules->isUnique(['order_detail_product_id', 'product_id', 'customer_id'], 'Anda sudah pernah melakukan review'));

        return $rules;
    }

    public function afterSave(\Cake\Event\Event $event,  \App\Model\Entity\ProductRating $entity, \ArrayObject $options)
    {
        if (!$entity->isNew()) {
            $product_id = $entity->get('product_id');

            $product_ratings = $this->find();
            $product_ratings = $product_ratings
                ->select([
                    'rate' => $product_ratings->func()->avg('rating'),
                    'total' => $product_ratings->func()->count('*')
                ])
                ->where([
                    'product_id' => $product_id,
                    'status' => 1
                ])
                ->first();
            if ($product_ratings) {
                try {
                    $product = $this->Products->get($product_id);
                    if ($product) {
                        $product->set('rating', $product_ratings->get('rate'));
                        $product->set('rating_count', $product_ratings->get('total'));
                        $this->Products->save($product);
                    }
                } catch(\Exception $e) {}
            }

        }
    }
}
