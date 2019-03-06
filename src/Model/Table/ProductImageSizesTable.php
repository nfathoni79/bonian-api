<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductImageSizes Model
 *
 * @property \App\Model\Table\ProductImagesTable|\Cake\ORM\Association\BelongsTo $ProductImages
 *
 * @method \App\Model\Entity\ProductImageSize get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductImageSize newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductImageSize[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductImageSize|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductImageSize|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductImageSize patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductImageSize[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductImageSize findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductImageSizesTable extends Table
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

        $this->setTable('product_image_sizes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ProductImages', [
            'foreignKey' => 'product_image_id',
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
            ->scalar('dimension')
            ->maxLength('dimension', 9)
            ->requirePresence('dimension', 'create')
            ->allowEmptyString('dimension', false);

        $validator
            ->scalar('path')
            ->maxLength('path', 255)
            ->requirePresence('path', 'create')
            ->allowEmptyString('path', false);

        $validator
            ->integer('size')
            ->allowEmptyString('size');

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
        $rules->add($rules->existsIn(['product_image_id'], 'ProductImages'));

        return $rules;
    }
}
