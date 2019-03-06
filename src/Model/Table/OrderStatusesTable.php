<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderStatuses Model
 *
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\HasMany $OrderDetails
 *
 * @method \App\Model\Entity\OrderStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrderStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrderStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderStatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class OrderStatusesTable extends Table
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

        $this->setTable('order_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('OrderDetails', [
            'foreignKey' => 'order_status_id'
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
            ->maxLength('name', 32)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }
}
