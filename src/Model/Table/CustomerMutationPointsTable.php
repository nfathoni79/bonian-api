<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * CustomerMutationPoints Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\CustomerMutationPointTypesTable|\Cake\ORM\Association\BelongsTo $CustomerMutationPointTypes
 *
 * @method \App\Model\Entity\CustomerMutationPoint get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerMutationPoint findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerMutationPointsTable extends Table
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

        $this->setTable('customer_mutation_points');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id'
        ]);
        $this->belongsTo('CustomerMutationPointTypes', [
            'foreignKey' => 'customer_mutation_point_type_id'
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

        $validator
            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->allowEmptyString('amount', false);

        $validator
            ->numeric('balance')
            ->requirePresence('balance', 'create')
            ->allowEmptyString('balance', false);

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
        $rules->add($rules->existsIn(['customer_mutation_point_type_id'], 'CustomerMutationPointTypes'));

        return $rules;
    }

    public function saving($customerId, $transaction_id, $amount,$description) {
        $amount = bcmul( $amount,'1');
        $customerBalances = TableRegistry::get('CustomerBalances');
        $pointRates = TableRegistry::get('CustomerPointRates');

        $rates = $pointRates->find()->first();
        $getSaldo = $customerBalances->find()
            ->where(['customer_id' => $customerId])
            ->first();
        if($getSaldo){
            $amount = bcmul($amount , $rates->get('value'));
            $saldo = $getSaldo->get('point');
            $balance = bcadd($saldo,$amount);

            if($balance >= 0){

                $data = $this->newEntity();
                $data->customer_id = $customerId;
                $data->customer_mutation_point_type_id = $transaction_id;
                $data->amount = $amount;
                $data->balance = $balance;
                $data->description = $description;
                if($this->save($data)){
                    $customerBalances->query()
                        ->update()
                        ->set(['point' => $balance, 'modified_point' => date('Y-m-d H:i:s')])
                        ->where(['id' => $getSaldo->get('id')])
                        ->execute();
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
