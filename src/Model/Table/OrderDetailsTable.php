<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderDetails Model
 *
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\BelongsTo $Orders
 * @property \App\Model\Table\BranchesTable|\Cake\ORM\Association\BelongsTo $Branches
 * @property \App\Model\Table\CourriersTable|\Cake\ORM\Association\BelongsTo $Courriers
 * @property \App\Model\Table\SubdistrictsTable|\Cake\ORM\Association\BelongsTo $Subdistricts
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\OrderStatusesTable|\Cake\ORM\Association\BelongsTo $OrderStatuses
 * @property \App\Model\Table\ChatsTable|\Cake\ORM\Association\HasMany $Chats
 * @property \App\Model\Table\OrderDetailProductsTable|\Cake\ORM\Association\HasMany $OrderDetailProducts
 * @property \App\Model\Table\OrderShippingDetailsTable|\Cake\ORM\Association\HasMany $OrderShippingDetails
 *
 * @method \App\Model\Entity\OrderDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrderDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrderDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrderDetailsTable extends Table
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

        $this->setTable('order_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Branches', [
            'foreignKey' => 'branch_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Courriers', [
            'foreignKey' => 'courrier_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Subdistricts', [
            'foreignKey' => 'subdistrict_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Provinces', [
            'foreignKey' => 'province_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('OrderStatuses', [
            'foreignKey' => 'order_status_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Chats', [
            'foreignKey' => 'order_detail_id'
        ]);
        $this->hasMany('OrderDetailProducts', [
            'foreignKey' => 'order_detail_id'
        ]);
        $this->hasMany('OrderShippingDetails', [
            'foreignKey' => 'order_detail_id'
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

        /*$validator
            ->scalar('awb')
            ->maxLength('awb', 50)
            ->requirePresence('awb', 'create')
            ->allowEmptyString('awb', false);*/



        /*$validator
            ->numeric('product_price')
            ->requirePresence('product_price', 'create')
            ->allowEmptyString('product_price', false);*/

        $validator
            ->numeric('shipping_cost')
            ->requirePresence('shipping_cost', 'create')
            ->allowEmptyString('shipping_cost', false);

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
        $rules->add($rules->existsIn(['order_id'], 'Orders'));
        $rules->add($rules->existsIn(['branch_id'], 'Branches'));
        $rules->add($rules->existsIn(['courrier_id'], 'Courriers'));
        $rules->add($rules->existsIn(['province_id'], 'Provinces'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['subdistrict_id'], 'Subdistricts'));
        $rules->add($rules->existsIn(['order_status_id'], 'OrderStatuses'));

        return $rules;
    }
}
