<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductAttributes Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\AttributeNamesTable|\Cake\ORM\Association\BelongsTo $AttributeNames
 * @property \App\Model\Table\AttributesTable|\Cake\ORM\Association\BelongsTo $Attributes
 *
 * @method \App\Model\Entity\ProductAttribute get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductAttribute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductAttribute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductAttribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductAttribute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductAttribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductAttribute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductAttribute findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductAttributesTable extends Table
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

        $this->setTable('product_attributes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AttributeNames', [
            'foreignKey' => 'attribute_name_id'
        ]);
        $this->belongsTo('Attributes', [
            'foreignKey' => 'attribute_id',
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', false);

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['attribute_name_id'], 'AttributeNames'));
        $rules->add($rules->existsIn(['attribute_id'], 'Attributes'));

        return $rules;
    }
}
