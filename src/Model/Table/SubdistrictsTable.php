<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Subdistricts Model
 *
 * @property \App\Model\Table\CitiesTable|\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\BranchesTable|\Cake\ORM\Association\HasMany $Branches
 * @property \App\Model\Table\CustomerAddresesTable|\Cake\ORM\Association\HasMany $CustomerAddreses
 *
 * @method \App\Model\Entity\Subdistrict get($primaryKey, $options = [])
 * @method \App\Model\Entity\Subdistrict newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Subdistrict[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Subdistrict|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subdistrict|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subdistrict patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Subdistrict[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Subdistrict findOrCreate($search, callable $callback = null, $options = [])
 */
class SubdistrictsTable extends Table
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

        $this->setTable('subdistricts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Branches', [
            'foreignKey' => 'subdistrict_id'
        ]);
        $this->hasMany('CustomerAddreses', [
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

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
        $rules->add($rules->existsIn(['city_id'], 'Cities'));

        return $rules;
    }
}
