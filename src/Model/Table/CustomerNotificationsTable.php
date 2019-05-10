<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerNotifications Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\CustomerNotificationTypesTable|\Cake\ORM\Association\BelongsTo $CustomerNotificationTypes
 *
 * @method \App\Model\Entity\CustomerNotification get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerNotification newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerNotification[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerNotification|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerNotification|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerNotification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerNotification[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerNotification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerNotificationsTable extends Table
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

        $this->setTable('customer_notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CustomerNotificationTypes', [
            'foreignKey' => 'customer_notification_type_id',
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

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->scalar('model')
            ->maxLength('model', 255)
            ->allowEmptyString('model');

        $validator
            ->integer('foreign_key')
            ->allowEmptyString('foreign_key');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 50)
            ->allowEmptyString('controller');

        $validator
            ->scalar('action')
            ->maxLength('action', 50)
            ->allowEmptyString('action');

        $validator
            ->boolean('is_read')
            ->allowEmptyString('is_read');

        $validator
            ->scalar('template')
            ->maxLength('template', 50)
            ->allowEmptyString('template');

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
        $rules->add($rules->existsIn(['customer_notification_type_id'], 'CustomerNotificationTypes'));

        return $rules;
    }
}
