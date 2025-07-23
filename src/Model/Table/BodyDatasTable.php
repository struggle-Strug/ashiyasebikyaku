<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class BodyDatasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('body_datas');
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
        $textFields = [
            'date',
            'ankle',
            'instep',
            'vertical_arch',
            'horizontal_arch',
            'toes',
        ];
        foreach ($textFields as $field) {
            $validator->allowEmptyString($field);
        }

        return $validator;
    }
}
