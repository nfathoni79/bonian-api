<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerDigitalInquiry Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\CustomerDigitalInquiry get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerDigitalInquiry findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerDigitalInquiryTable extends Table
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

        $this->setTable('customer_digital_inquiry');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id'
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
            ->scalar('customer_number')
            ->maxLength('customer_number', 20)
            ->allowEmptyString('customer_number');

        $validator
            ->scalar('code')
            ->maxLength('code', 15)
            ->allowEmptyString('code');

        $validator
            ->boolean('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('raw_request')
            ->allowEmptyString('raw_request');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
