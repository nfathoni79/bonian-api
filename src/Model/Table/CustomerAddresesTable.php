<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerAddreses Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\ProvincesTable|\Cake\ORM\Association\BelongsTo $Provinces
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\SubdistrictsTable|\Cake\ORM\Association\BelongsTo $Subdistricts
 *
 * @method \App\Model\Entity\CustomerAddrese get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerAddrese newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerAddrese[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerAddrese|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerAddrese|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerAddrese patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerAddrese[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerAddrese findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerAddresesTable extends Table
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

        $this->setTable('customer_addreses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id'
        ]);
        $this->belongsTo('Provinces', [
            'foreignKey' => 'province_id'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->belongsTo('Subdistricts', [
            'foreignKey' => 'subdistrict_id'
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
            ->notBlank('postal_code', 'Kodepos tidak boleh kosong')
            ->numeric('postal_code', 'Kodepos harus anga')
            ->maxLength('postal_code',  5,'Kodepos harus 5 digit')
            ->minLength('postal_code', 5, 'Kodepos harus 5 digit');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->allowEmptyString('address', false);

        $validator
            ->requirePresence('province_id')
            ->notBlank('province_id');

        $validator
            ->requirePresence('city_id', 'created')
            ->notBlank('city_id');

        $validator
            ->requirePresence('subdistrict_id', 'created')
            ->notBlank('subdistrict_id');

        $validator
            ->requirePresence('title')
            ->notBlank('title');

        $validator
            ->requirePresence('recipient_name')
            ->notBlank('recipient_name');

        $validator
            ->requirePresence('recipient_phone', 'created')
            ->notBlank('recipient_phone');

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
        $rules->add($rules->existsIn(['province_id'], 'Provinces'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['subdistrict_id'], 'Subdistricts'));

        return $rules;
    }
}
