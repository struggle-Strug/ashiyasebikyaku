<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Habits Table
 */
class HabitsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('habits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')->requirePresence('user_id', 'create')->notEmptyString('user_id')
            ->scalar('occupation')->maxLength('occupation', 255)->allowEmptyString('occupation')
            ->scalar('eating_habits')->maxLength('eating_habits', 255)->allowEmptyString('eating_habits')
            ->scalar('sleep')->maxLength('sleep', 255)->allowEmptyString('sleep')
            ->scalar('stress_factors')->maxLength('stress_factors', 255)->allowEmptyString('stress_factors')
            ->scalar('exercise_history')->maxLength('exercise_history', 255)->allowEmptyString('exercise_history')
            ->scalar('medical_history')->maxLength('medical_history', 255)->allowEmptyString('medical_history')
            ->scalar('other_notes')->maxLength('other_notes', 255)->allowEmptyString('other_notes');

        return $validator;
    }
}
