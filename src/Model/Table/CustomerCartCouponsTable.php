<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerCartCoupons Model
 *
 * @property \App\Model\Table\CustomerCartsTable|\Cake\ORM\Association\BelongsTo $CustomerCarts
 * @property \App\Model\Table\ProductCouponsTable|\Cake\ORM\Association\BelongsTo $ProductCoupons
 *
 * @method \App\Model\Entity\CustomerCartCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerCartCoupon findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerCartCouponsTable extends Table
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

        $this->setTable('customer_cart_coupons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CustomerCarts', [
            'foreignKey' => 'customer_cart_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductCoupons', [
            'foreignKey' => 'product_coupon_id',
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
        $rules->add($rules->existsIn(['customer_cart_id'], 'CustomerCarts'));
        $rules->add($rules->existsIn(['product_coupon_id'], 'ProductCoupons'));

        return $rules;
    }
}
