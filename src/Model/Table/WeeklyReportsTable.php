<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WeeklyReports Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\HasMany $Photos
 *
 * @method \App\Model\Entity\WeeklyReport newEmptyEntity()
 * @method \App\Model\Entity\WeeklyReport newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\WeeklyReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WeeklyReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\WeeklyReport findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\WeeklyReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WeeklyReport[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WeeklyReport|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WeeklyReport saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WeeklyReport[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WeeklyReport[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\WeeklyReport[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WeeklyReport[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WeeklyReportsTable extends Table
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

        $this->setTable('weekly_reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Photos', [
            'foreignKey' => 'weekly_report_id',
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->date('report_date')
            ->requirePresence('report_date', 'create')
            ->notEmptyDate('report_date');

        $validator
            ->scalar('weight')
            ->maxLength('weight', 10)
            ->allowEmptyString('weight');

        $validator
            ->scalar('fat_percent')
            ->maxLength('fat_percent', 10)
            ->allowEmptyString('fat_percent');

        $validator
            ->scalar('size1')
            ->maxLength('size1', 10)
            ->allowEmptyString('size1');

        $validator
            ->scalar('size2')
            ->maxLength('size2', 10)
            ->allowEmptyString('size2');

        $validator
            ->scalar('size3')
            ->maxLength('size3', 10)
            ->allowEmptyString('size3');

        $validator
            ->scalar('size4')
            ->maxLength('size4', 10)
            ->allowEmptyString('size4');

        $validator
            ->scalar('size5')
            ->maxLength('size5', 10)
            ->allowEmptyString('size5');

        $validator
            ->scalar('size6')
            ->maxLength('size6', 10)
            ->allowEmptyString('size6');

        $validator
            ->scalar('size7')
            ->maxLength('size7', 10)
            ->allowEmptyString('size7');

        $validator
            ->scalar('size8')
            ->maxLength('size8', 10)
            ->allowEmptyString('size8');

        $validator
            ->scalar('size9')
            ->maxLength('size9', 10)
            ->allowEmptyString('size9');

        $validator
            ->notEmptyString('is_draft');

        $validator
            ->notEmptyString('is_checked');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
