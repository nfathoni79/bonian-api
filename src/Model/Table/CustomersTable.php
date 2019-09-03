<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 *
 * @property \App\Model\Table\RefferalCustomersTable|\Cake\ORM\Association\BelongsTo $RefferalCustomers
 * @property \App\Model\Table\CustomerGroupsTable|\Cake\ORM\Association\BelongsTo $CustomerGroups
 * @property \App\Model\Table\CustomerStatusesTable|\Cake\ORM\Association\BelongsTo $CustomerStatuses
 * @property \App\Model\Table\ChatDetailsTable|\Cake\ORM\Association\HasMany $ChatDetails
 * @property \App\Model\Table\CustomerAddresesTable|\Cake\ORM\Association\HasMany $CustomerAddreses
 * @property \App\Model\Table\CustomerBalancesTable|\Cake\ORM\Association\HasMany $CustomerBalances
 * @property \App\Model\Table\CustomerBuyGroupDetailsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroupDetails
 * @property \App\Model\Table\CustomerBuyGroupsTable|\Cake\ORM\Association\HasMany $CustomerBuyGroups
 * @property \App\Model\Table\CustomerLogBrowsingsTable|\Cake\ORM\Association\HasMany $CustomerLogBrowsings
 * @property \App\Model\Table\CustomerMutationAmountsTable|\Cake\ORM\Association\HasMany $CustomerMutationAmounts
 * @property \App\Model\Table\CustomerMutationPointsTable|\Cake\ORM\Association\HasMany $CustomerMutationPoints
 * @property \App\Model\Table\CustomerTokensTable|\Cake\ORM\Association\HasMany $CustomerTokens
 * @property \App\Model\Table\CustomerVirtualAccountTable|\Cake\ORM\Association\HasMany $CustomerVirtualAccount
 * @property \App\Model\Table\GenerationsTable|\Cake\ORM\Association\HasMany $Generations
 * @property \App\Model\Table\OrdersTable|\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\Customer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Customer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Customer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Customer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Customer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Customer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomersTable extends Table
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

        $this->setTable('customers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

//        $this->belongsTo('RefferalCustomers', [
//            'foreignKey' => 'refferal_customer_id',
//            'joinType' => 'INNER'
//        ]);
        $this->belongsTo('CustomerGroups', [
            'foreignKey' => 'customer_group_id'
        ]);
        $this->belongsTo('CustomerStatuses', [
            'foreignKey' => 'customer_status_id'
        ]);
        $this->belongsTo('ReferralCustomer', [
            'className' => 'Customers',
            'joinType' => 'LEFT',
            'foreignKey' => 'refferal_customer_id'
        ]);
        $this->hasMany('ChatDetails', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerAddreses', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBalances', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBuyGroupDetails', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerBuyGroups', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerLogBrowsings', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerMutationAmounts', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerMutationPoints', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerTokens', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomerVirtualAccount', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Generations', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Orders', [
            'foreignKey' => 'customer_id'
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
            ->scalar('reffcode')
            ->maxLength('reffcode', 10)
            ->requirePresence('reffcode', 'create')
            ->allowEmptyString('reffcode', false);

        $validator
            ->scalar('username')
            ->maxLength('username', 30)
            ->requirePresence('username', 'create', 'Username harus diisi')
            ->alphaNumeric('username', 'Username harus terdiri dari huruf atau angka')
            ->allowEmptyString('username', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create', 'Email harus diisi')
            ->notBlank('email', 'Email tidak boleh kosong')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Email sudah terdaftar']);

        $validator
            ->requirePresence('password', 'create', 'Password harus diisi')
            ->notBlank('password', 'Password harus diisi', 'create')
            ->lengthBetween('password', [6, 20], 'password min 6 - 20 character')
            ->regex('password', '/^(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', 'Password harus mengandung 6 karakter, min. 1 huruf besar dan 1 huruf kecil karakter');


        $validator
            ->requirePresence('cpassword', 'create')
            ->notEmpty('cpassword')
            ->allowEmpty('cpassword', function ($context) {
                return !isset($context['data']['password']);
            })
            ->equalToField('cpassword', 'password', 'Konfirmasi password tidak sama')
            ->add('cpassword', 'compareWith', [
                'rule' => ['compareWith', 'password'],
                'message' => 'Passwords do not match.'
            ]);

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 40)
            ->requirePresence('first_name', 'create', 'Nama depan tidak boleh kosong')
            ->allowEmptyString('first_name', false);

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 30)
            ->requirePresence('last_name', 'create')
            ->allowEmptyString('last_name', false);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 15)
            ->requirePresence('phone', 'create')
            ->allowEmptyString('phone', false);

//        $validator
//            ->date('dob')
//            ->requirePresence('dob', 'create')
//            ->allowEmptyDate('dob', false);

        $validator
            ->integer('is_verified')
            ->requirePresence('is_verified', 'create')
            ->allowEmptyString('is_verified', false);

        $validator
            ->scalar('platforrm')
            ->maxLength('platforrm', 15)
            ->requirePresence('platforrm', 'create')
            ->allowEmptyString('platforrm', false);

        return $validator;
    }

    public function validationPassword(Validator $validator)
    {
        $validator
            ->notBlank('password', 'Kolom ini harus diisi')
            ->lengthBetween('password', [6, 20], 'password min 6 - 20 character')
            ->regex('password', '/^(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',
                'kata sandi setidaknya mengandung 1 huruf besar, 1 huruf kecil dan 1 angka');

        $validator
            ->equalToField('repeat_password', 'password', 'konfirmasi kata Sandi baru tidak sama')
            ->allowEmpty('repeat_password', function ($context) {
                return !isset($context['data']['password']);
            }, 'Kolom ini harus diisi');
        return $validator;
    }

    public function validationPasswords(Validator $validator)
    {
        $validator
            ->lengthBetween('password', [6, 20], 'password min 6 - 20 character')
            ->regex('password', '/^(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',
                'password min 6 char at least one uppercase letter, one lowercase letter and one number');

        $validator
            ->equalToField('repeat_password', 'password', 'Repeat password does not match with your password')
            ->allowEmpty('repeat_password', function ($context) {
                return !isset($context['data']['password']);
            });
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
        $rules->add($rules->isUnique(['email'], 'Email sudah terdaftar'));
        $rules->add($rules->isUnique(['username'], 'Username sudah terdaftar'));
        $rules->add($rules->isUnique(['reffcode']));
        $rules->add($rules->isUnique(['phone'], 'Nomor handphone sudah terdaftar'));
//        $rules->add($rules->existsIn(['refferal_customer_id'], 'RefferalCustomers'));
        $rules->add($rules->existsIn(['customer_group_id'], 'CustomerGroups'));
        $rules->add($rules->existsIn(['customer_status_id'], 'CustomerStatuses'));

        return $rules;
    }

    public function checkRefferal($id){
        /* check refferal already registered or not */
        $find = $this->find()
            ->where(['id' => $id, 'refferal_customer_id != ' => '0'])
            ->first();
        if($find){
            return false;
        }else{
            return true;
        }
    }

    public function getRefferalCode($id){
        /* check refferal already registered or not */
        $find = $this->find()
            ->where(['id' => $id])
            ->first();
        if($find){
            return $find->get('reffcode');
        }
    }
    public function checkRefferalCode($reff, $customer_id){
        /* check refferal already registered or not */
        $find = $this->find()
            ->where(['reffcode' => $reff])
            ->first();
        if($find){
            if($find->get('id') == $customer_id){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
