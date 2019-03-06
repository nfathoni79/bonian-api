<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Branches Model
 *
 * @property \App\Model\Table\ProvincesTable|\Cake\ORM\Association\BelongsTo $Provinces
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\SubdistrictsTable|\Cake\ORM\Association\BelongsTo $Subdistricts
 * @property \App\Model\Table\OrderDetailsTable|\Cake\ORM\Association\HasMany $OrderDetails
 * @property \App\Model\Table\ProductBranchesTable|\Cake\ORM\Association\HasMany $ProductBranches
 * @property \App\Model\Table\ProductOptionStocksTable|\Cake\ORM\Association\HasMany $ProductOptionStocks
 * @property \App\Model\Table\ProductStockMutationsTable|\Cake\ORM\Association\HasMany $ProductStockMutations
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Branch get($primaryKey, $options = [])
 * @method \App\Model\Entity\Branch newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Branch[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Branch|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Branch|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Branch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Branch[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Branch findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BranchesTable extends Table
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

        $this->setTable('branches');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Provinces', [
            'foreignKey' => 'provice_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Subdistricts', [
            'foreignKey' => 'subdistrict_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('OrderDetails', [
            'foreignKey' => 'branch_id'
        ]);
        $this->hasMany('ProductBranches', [
            'foreignKey' => 'branch_id'
        ]);
        $this->hasMany('ProductOptionStocks', [
            'foreignKey' => 'branch_id'
        ]);
        $this->hasMany('ProductStockMutations', [
            'foreignKey' => 'branch_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'branch_id'
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('address')
            ->requirePresence('address', 'create')
            ->allowEmptyString('address', false);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 15)
            ->requirePresence('phone', 'create')
            ->allowEmptyString('phone', false);

        $validator
            ->numeric('latitude')
            ->requirePresence('latitude', 'create')
            ->allowEmptyString('latitude', false);

        $validator
            ->numeric('longitude')
            ->requirePresence('longitude', 'create')
            ->allowEmptyString('longitude', false);

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
        $rules->add($rules->existsIn(['provice_id'], 'Provinces'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['subdistrict_id'], 'Subdistricts'));

        return $rules;
    }
}
