<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class DifferencesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('differences');
        $this->setDisplayField('milestone');
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

        $validator->allowEmptyString('milestone');
        $validator->allowEmptyString('category');
        $validator->allowEmptyString('target1');
        $validator->allowEmptyString('target2');
        $validator->allowEmptyString('evaluation1');
        $validator->allowEmptyString('evaluation2');
        $validator->allowEmptyString('comment1');
        $validator->allowEmptyString('comment2');

        return $validator;
    }
}
