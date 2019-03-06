<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vouchers Model
 *
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\Voucher get($primaryKey, $options = [])
 * @method \App\Model\Entity\Voucher newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Voucher[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Voucher|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Voucher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Voucher findOrCreate($search, callable $callback = null, $options = [])
 */
class VouchersTable extends Table
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

        $this->setTable('vouchers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Orders', [
            'foreignKey' => 'voucher_id'
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
            ->scalar('code_voucher')
            ->maxLength('code_voucher', 8)
            ->requirePresence('code_voucher', 'create')
            ->allowEmptyString('code_voucher', false);

        $validator
            ->dateTime('date_start')
            ->requirePresence('date_start', 'create')
            ->allowEmptyDateTime('date_start', false);

        $validator
            ->dateTime('date_end')
            ->requirePresence('date_end', 'create')
            ->allowEmptyDateTime('date_end', false);

        $validator
            ->integer('qty')
            ->requirePresence('qty', 'create')
            ->allowEmptyString('qty', false);

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->allowEmptyString('type', false);

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->allowEmptyString('value', false);

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        return $validator;
    }
}
