<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attributes Model
 *
 * @property \App\Model\Table\AttributesTable|\Cake\ORM\Association\BelongsTo $ParentAttributes
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\BelongsTo $ProductCategories
 * @property \App\Model\Table\AttributesTable|\Cake\ORM\Association\HasMany $ChildAttributes
 * @property \App\Model\Table\ProductAttributesTable|\Cake\ORM\Association\HasMany $ProductAttributes
 *
 * @method \App\Model\Entity\Attribute get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attribute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Attribute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attribute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class AttributesTable extends Table
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

        $this->setTable('attributes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentAttributes', [
            'className' => 'Attributes',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('ProductCategories', [
            'foreignKey' => 'product_category_id'
        ]);
        $this->hasMany('ChildAttributes', [
            'className' => 'Attributes',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ProductAttributes', [
            'foreignKey' => 'attribute_id'
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentAttributes'));
        $rules->add($rules->existsIn(['product_category_id'], 'ProductCategories'));

        return $rules;
    }
}
