<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SearchCategories Model
 *
 * @property \App\Model\Table\SearchTermsTable|\Cake\ORM\Association\BelongsTo $SearchTerms
 * @property \App\Model\Table\ProductCategoriesTable|\Cake\ORM\Association\BelongsTo $ProductCategories
 *
 * @method \App\Model\Entity\SearchCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\SearchCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SearchCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SearchCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SearchCategory|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SearchCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SearchCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SearchCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SearchCategoriesTable extends Table
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

        $this->setTable('search_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('SearchTerms', [
            'foreignKey' => 'search_term_id'
        ]);
        $this->belongsTo('ProductCategories', [
            'foreignKey' => 'product_category_id'
        ]);
        $this->belongsTo('Browsers', [
            'foreignKey' => 'browser_id'
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
        $rules->add($rules->existsIn(['search_term_id'], 'SearchTerms'));
        //$rules->add($rules->existsIn(['product_category_id'], 'ProductCategories'));

        return $rules;
    }
}
