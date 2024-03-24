<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\StaffsTable&\Cake\ORM\Association\BelongsTo $Staffs
 * @property \App\Model\Table\DailyReportsTable&\Cake\ORM\Association\HasMany $DailyReports
 * @property \App\Model\Table\WeeklyReportsTable&\Cake\ORM\Association\HasMany $WeeklyReports
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Staffs', [
            'foreignKey' => 'staff_id',
        ]);
        $this->hasMany('DailyReports', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('WeeklyReports', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('UserPoints', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('member_number')
            ->maxLength('member_number', 50)
            ->requirePresence('member_number', 'create')
            ->notEmptyString('member_number');

        $validator
            ->scalar('uuid')
            ->maxLength('uuid', 100)
            ->requirePresence('uuid', 'create')
            ->notEmptyString('uuid');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 100)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 50)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 50)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name_kana')
            ->maxLength('last_name_kana', 50)
            ->allowEmptyString('last_name_kana');

        $validator
            ->scalar('first_name_kana')
            ->maxLength('first_name_kana', 50)
            ->allowEmptyString('first_name_kana');

        $validator
            ->scalar('zipcode')
            ->maxLength('zipcode', 10)
            ->allowEmptyString('zipcode');

        $validator
            ->scalar('prefecture')
            ->maxLength('prefecture', 10)
            ->allowEmptyString('prefecture');

        $validator
            ->scalar('city')
            ->maxLength('city', 100)
            ->allowEmptyString('city');

        $validator
            ->scalar('address')
            ->maxLength('address', 100)
            ->allowEmptyString('address');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 20)
            ->allowEmptyString('tel');

        $validator
            ->date('birthday')
            ->allowEmptyDate('birthday');

        $validator
            ->scalar('sextype')
            ->maxLength('sextype', 1)
            ->allowEmptyString('sextype');

        $validator
            ->notEmptyString('is_continue');

        $validator
            ->dateTime('start_datetime')
            ->requirePresence('start_datetime', 'create')
            ->notEmptyDateTime('start_datetime');

        $validator
            ->dateTime('end_datetime')
            ->allowEmptyDateTime('end_datetime');

        $validator
            ->integer('staff_id')
            ->allowEmptyString('staff_id');

        $validator
            ->scalar('payment')
            ->maxLength('payment', 100)
            ->allowEmptyString('payment');

        $validator
            ->scalar('sales_history')
            ->allowEmptyString('sales_history');

        $validator
            ->notEmptyString('light_plan');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

        $validator
            ->notEmptyString('is_deleted');
        
        $validator
            ->notEmptyString('is_light_plan_first_event');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn('staff_id', 'Staffs'), ['errorField' => 'staff_id']);

        return $rules;
    }
}
