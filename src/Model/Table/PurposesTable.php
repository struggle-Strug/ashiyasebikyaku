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

        // Add Timestamp behavior for automatic created/modified fields
        $this->addBehavior('Timestamp'); 

        // Define the association with the Users table
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER', // or 'LEFT' if it's optional
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $textFields = [
            'email',
            'name',
            'current_concerns_appearance',
            'current_concerns_function',
            'goal_appearance',
            'goal_function',
            'future_self_image',
            'next_two_weeks_work',
            'consulting_topics'
        ];
        foreach ($textFields as $field) {
            $validator->allowEmptyString($field);
        }

        return $validator;
    }
}
