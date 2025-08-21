<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SelfDatasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('self_datas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->notEmptyString('user_id', 'User ID is required');

        $validator
            ->date('date')
            ->allowEmptyDate('date');

        // Required, not empty
        $validator
            ->requirePresence('good_changes', 'create')
            ->notEmptyString('good_changes', '必須項目です。');

        $validator
            ->requirePresence('work_review', 'create')
            ->notEmptyString('work_review', '必須項目です。');

        $validator
            ->requirePresence('no_change_parts', 'create')
            ->notEmptyString('no_change_parts', '必須項目です。');

        // Optional fields
        $optionalFields = [
            'current_state',
            'next_two_weeks_work',
            'consult_topics',
            'consult_content'
        ];
        foreach ($optionalFields as $field) {
            $validator->allowEmptyString($field);
        }

        return $validator;
    }
}
