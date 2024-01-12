<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EventSchedules Model
 *
 * @property \App\Model\Table\EventsTable&\Cake\ORM\Association\BelongsTo $Events
 * @property \App\Model\Table\EventEntriesTable&\Cake\ORM\Association\HasMany $EventEntries
 *
 * @method \App\Model\Entity\EventSchedule newEmptyEntity()
 * @method \App\Model\Entity\EventSchedule newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EventSchedule[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EventSchedule get($primaryKey, $options = [])
 * @method \App\Model\Entity\EventSchedule findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EventSchedule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EventSchedule[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EventSchedule|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EventSchedule saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EventSchedule[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EventSchedule[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EventSchedule[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EventSchedule[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EventSchedulesTable extends Table
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

        $this->setTable('event_schedules');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Events', [
            'foreignKey' => 'event_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('EventEntries', [
            'foreignKey' => 'event_schedule_id',
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
            ->integer('event_id')
            ->notEmptyString('event_id');

        $validator
            ->date('event_date')
            ->requirePresence('event_date', 'create')
            ->notEmptyDate('event_date');

        $validator
            ->scalar('start_time')
            ->maxLength('start_time', 10)
            ->requirePresence('start_time', 'create')
            ->notEmptyString('start_time');

        $validator
            ->scalar('end_time')
            ->maxLength('end_time', 10)
            ->requirePresence('end_time', 'create')
            ->notEmptyString('end_time');

        $validator
            ->dateTime('cancel_datetime')
            ->requirePresence('cancel_datetime', 'create')
            ->notEmptyDateTime('cancel_datetime');

        $validator
            ->integer('capacity')
            ->requirePresence('capacity', 'create')
            ->notEmptyString('capacity');

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
        $rules->add($rules->existsIn('event_id', 'Events'), ['errorField' => 'event_id']);

        return $rules;
    }
}
