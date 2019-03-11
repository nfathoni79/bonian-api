<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductGroupDetails Model
 *
 * @property \App\Model\Table\ProductGroupsTable|\Cake\ORM\Association\BelongsTo $ProductGroups
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ProductGroupDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductGroupDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductGroupDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroupDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductGroupDetail|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductGroupDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroupDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductGroupDetail findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductGroupDetailsTable extends Table
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

        $this->setTable('product_group_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ProductGroups', [
            'foreignKey' => 'product_group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
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
            ->numeric('price_sale')
            ->allowEmptyString('price_sale');

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
        $rules->add($rules->existsIn(['product_group_id'], 'ProductGroups'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }

    public function checkStatusProduct($productId = null){
        $check = $this->find()
            ->contain('ProductGroups')
            ->where(['product_id' => $productId])
            ->order(['ProductGroupDetails.id' => 'DESC'])
            ->first();
        if($check){
            if($check['product_group']['status'] == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getPrices($productId = null){
        $check = $this->find()
            ->contain('ProductGroups')
            ->where(['product_id' => $productId])
            ->order(['ProductGroupDetails.id' => 'DESC'])
            ->first();
        if($check){
            if($check['product_group']['status'] == 1){
                return $check['price_sale'];
            }
        }
    }
}
