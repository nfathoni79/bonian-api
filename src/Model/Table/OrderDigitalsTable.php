<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderDigitals Model
 *
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\BelongsTo Orders
 * @property \App\Model\Table\DigitalDetailsTable|\Cake\ORM\Association\BelongsTo $DigitalDetails
 *
 * @method \App\Model\Entity\OrderDigital get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrderDigital newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrderDigital[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderDigital|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDigital|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderDigital patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDigital[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderDigital findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrderDigitalsTable extends Table
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

        $this->setTable('order_digitals');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id'
        ]);

        $this->belongsTo('DigitalDetails', [
            'foreignKey' => 'digital_detail_id'
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
            ->integer('order_detail')
            ->allowEmptyString('order_detail');

        $validator
            ->scalar('customer_number')
            ->maxLength('customer_number', 20)
            ->allowEmptyString('customer_number');

        $validator
            ->numeric('price')
            ->allowEmptyString('price');

        $validator
            ->scalar('raw_response')
            ->allowEmptyString('raw_response');

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
        $rules->add($rules->existsIn(['digital_detail_id'], 'DigitalDetails'));

        return $rules;
    }
}
