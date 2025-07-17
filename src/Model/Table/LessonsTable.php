<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LessonsTable
 *
 * レッスン情報管理テーブル
 * - 開催日、区分、講師、申込者を管理
 * - staff_id は Staffs テーブルを参照する外部キー
 */
class LessonsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('lessons');                  // 対応するDBテーブル名
        $this->setDisplayField('id');               // デフォルト表示フィールド
        $this->setPrimaryKey('id');                // プライマリキー

        $this->addBehavior('Timestamp');           // created, modified 自動管理

        $this->belongsTo('Staffs', [               // Staffs テーブルと紐付け
            'foreignKey' => 'staff_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->date('start_data')                      // 開催日（必須）
            ->requirePresence('start_data', 'create')
            ->notEmptyDate('start_data');

        $validator
            ->scalar('category')                   // 区分（例: 実践レッスン）
            ->maxLength('category', 50)
            ->requirePresence('category', 'create')
            ->notEmptyString('category');

        $validator
            ->integer('staff_id')             // 担当講師ID（必須）
            ->requirePresence('staff_id', 'create')
            ->notEmptyString('staff_id');

        $validator
            ->scalar('applicant_name')            // 申込者名（任意）
            ->maxLength('applicant_name', 100)
            ->notEmptyString('applicant_name');

        $validator
            ->scalar('consult_history')            // 指導履歴（任意）
            ->allowEmptyString('consult_history');

        return $validator;
    }
}
