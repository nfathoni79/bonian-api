<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductGroups Model
 *
 * @property \App\Model\Table\CustomerBuyGroupsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroups
 * @property \App\Model\Table\ProductGroupDetailsTable|\Cake\ORM\Association\HasMany $ProductGroupDetails
 *
 * @method \App\Model\Entity\ProductGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductGroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductGroupsTable extends Table
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

        $this->setTable('product_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('CustomerBuyGroups', [
            'foreignKey' => 'product_group_id'
        ]);
        $this->hasMany('ProductGroupDetails', [
            'foreignKey' => 'product_group_id'
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
            ->maxLength('name', 15)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->integer('value')
            ->requirePresence('value', 'create')
            ->allowEmptyString('value', false);

        $validator
            ->dateTime('date_start')
            ->requirePresence('date_start', 'create')
            ->allowEmptyDateTime('date_start', false);

        $validator
            ->dateTime('date_end')
            ->requirePresence('date_end', 'create')
            ->allowEmptyDateTime('date_end', false);

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmptyString('status', false);

        return $validator;
    }
}
