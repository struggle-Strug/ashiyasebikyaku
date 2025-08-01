<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PurposesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('purposes');
        $this->setDisplayField('name');
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
            ->requirePresence('email', 'create')
            ->notEmptyString('email', '必須項目です。');

        $validator
            ->requirePresence('name', 'create')
            ->notEmptyString('name', '必須項目です。');

        $validator
            ->requirePresence('current_concerns_appearance', 'create')
            ->notEmptyString('current_concerns_appearance', '必須項目です。');

        $validator
            ->requirePresence('current_concerns_function', 'create')
            ->notEmptyString('current_concerns_function', '必須項目です。');

        $validator
            ->requirePresence('goal_appearance', 'create')
            ->notEmptyString('goal_appearance', '必須項目です。');

        $validator
            ->requirePresence('goal_function', 'create')
            ->notEmptyString('goal_function', '必須項目です。');

        $validator
            ->requirePresence('future_self_image', 'create')
            ->notEmptyString('future_self_image', '必須項目です。');

        $validator
            ->requirePresence('next_two_weeks_work', 'create')
            ->notEmptyString('next_two_weeks_work', '必須項目です。');

        $validator
            ->requirePresence('consulting_topics', 'create')
            ->notEmptyString('consulting_topics', '必須項目です。');

        return $validator;
    }
}
