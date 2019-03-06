<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmailQueue Model
 *
 * @method \App\Model\Entity\EmailQueue get($primaryKey, $options = [])
 * @method \App\Model\Entity\EmailQueue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EmailQueue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmailQueue|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EmailQueue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmailQueue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmailQueueTable extends Table
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

        $this->setTable('email_queue');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->scalar('from_name')
            ->maxLength('from_name', 255)
            ->allowEmptyString('from_name');

        $validator
            ->scalar('from_email')
            ->maxLength('from_email', 255)
            ->allowEmptyString('from_email');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->allowEmptyString('subject', false);

        $validator
            ->scalar('template')
            ->maxLength('template', 50)
            ->requirePresence('template', 'create')
            ->allowEmptyString('template', false);

        $validator
            ->scalar('layout')
            ->maxLength('layout', 50)
            ->requirePresence('layout', 'create')
            ->allowEmptyString('layout', false);

        $validator
            ->scalar('theme')
            ->maxLength('theme', 50)
            ->allowEmptyString('theme');

        $validator
            ->scalar('format')
            ->maxLength('format', 5)
            ->requirePresence('format', 'create')
            ->allowEmptyString('format', false);

        $validator
            ->scalar('text')
            ->allowEmptyString('text');

        $validator
            ->scalar('html')
            ->allowEmptyString('html');

        $validator
            ->scalar('headers')
            ->allowEmptyString('headers');

        $validator
            ->integer('sent')
            ->requirePresence('sent', 'create')
            ->allowEmptyString('sent', false);

        $validator
            ->integer('locked')
            ->requirePresence('locked', 'create')
            ->allowEmptyString('locked', false);

        $validator
            ->scalar('attachments')
            ->allowEmptyString('attachments');

        $validator
            ->integer('send_tries')
            ->requirePresence('send_tries', 'create')
            ->allowEmptyString('send_tries', false);

        $validator
            ->scalar('error')
            ->allowEmptyString('error');

        $validator
            ->dateTime('send_at')
            ->allowEmptyDateTime('send_at');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
