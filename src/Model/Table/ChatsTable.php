<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Chats Model
 *
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\BelongsTo $OrderDetails
 * @property \App\Model\Table\ChatDetailsTable|\Cake\ORM\Association\HasMany $ChatDetails
 *
 * @method \App\Model\Entity\Chat get($primaryKey, $options = [])
 * @method \App\Model\Entity\Chat newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Chat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Chat|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Chat|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Chat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Chat[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Chat findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChatsTable extends Table
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

        $this->setTable('chats');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('OrderDetails', [
            'foreignKey' => 'order_detail_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChatDetails', [
            'foreignKey' => 'chat_id'
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
        $rules->add($rules->existsIn(['order_detail_id'], 'OrderDetails'));

        return $rules;
    }
}
