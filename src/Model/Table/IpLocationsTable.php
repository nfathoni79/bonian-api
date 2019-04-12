<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IpLocations Model
 *
 * @method \App\Model\Entity\IpLocation get($primaryKey, $options = [])
 * @method \App\Model\Entity\IpLocation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IpLocation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IpLocation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IpLocation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IpLocation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IpLocation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IpLocation findOrCreate($search, callable $callback = null, $options = [])
 */
class IpLocationsTable extends Table
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

        $this->setTable('ip_locations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('ip')
            ->maxLength('ip', 15)
            ->allowEmptyString('ip');

        $validator
            ->scalar('city')
            ->maxLength('city', 50)
            ->allowEmptyString('city');

        $validator
            ->scalar('region')
            ->maxLength('region', 50)
            ->allowEmptyString('region');

        $validator
            ->scalar('country_name')
            ->maxLength('country_name', 30)
            ->allowEmptyString('country_name');

        $validator
            ->scalar('country_code')
            ->maxLength('country_code', 5)
            ->allowEmptyString('country_code');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->scalar('asn')
            ->maxLength('asn', 30)
            ->allowEmptyString('asn');

        $validator
            ->scalar('organisation')
            ->maxLength('organisation', 30)
            ->allowEmptyString('organisation');

        return $validator;
    }
}
