<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\VouchersTable|\Cake\ORM\Association\BelongsTo $Vouchers
 * @property \App\Model\Table\ProductPromotionsTable|\Cake\ORM\Association\BelongsTo $ProductPromotions
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\HasMany $OrderDetails
 * @property \App\Model\Table\OrderDigitalsTable|\Cake\ORM\Association\hasOne $OrderDigitals
 *
 * @method \App\Model\Entity\Order get($primaryKey, $options = [])
 * @method \App\Model\Entity\Order newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Order[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
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

        $this->setTable('orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Vouchers', [
            'foreignKey' => 'voucher_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('ProductPromotions', [
            'foreignKey' => 'product_promotion_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Provinces', [
            'foreignKey' => 'province_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Subdistricts', [
            'foreignKey' => 'subdistrict_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('OrderDetails', [
            'foreignKey' => 'order_id'
        ]);
        $this->hasMany('Transactions', [
            'foreignKey' => 'order_id'
        ]);
        $this->hasMany('ProductRatings', [
            'foreignKey' => 'order_id'
        ]);
        $this->hasOne('OrderDigitals', [
            'foreignKey' => 'order_id'
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
            ->scalar('invoice')
            ->maxLength('invoice', 15)
            ->requirePresence('invoice', 'create')
            ->allowEmptyString('invoice', false);

        /*$validator
            ->scalar('address')
            ->requirePresence('address', 'create')
            ->allowEmptyString('address', false);*/

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['voucher_id'], 'Vouchers'));
        $rules->add($rules->existsIn(['product_promotion_id'], 'ProductPromotions'));
        $rules->add($rules->existsIn(['province_id'], 'Provinces'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['subdistrict_id'], 'Subdistricts'));

        return $rules;
    }
}
