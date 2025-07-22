<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ConsultsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('consults');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Staffs', [
            'foreignKey' => 'staff_id',
            'joinType' => 'LEFT',
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

        $validator->allowEmptyString('category');
        $validator->allowEmptyString('theme');
        $validator->allowEmptyString('instruction');
        $validator->allowEmptyString('note');
        $validator->allowEmptyString('next_step');

        $validator->integer('staff_id')
            ->allowEmptyString('staff_id');

        return $validator;
    }
}
