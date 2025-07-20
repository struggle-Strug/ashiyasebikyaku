<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Habit Entity
 *
 * @property int $id                     習慣ID (primary key)
 * @property int $user_id               ユーザーID (Usersテーブル参照)
 * @property string $occupation          職業
 * @property string $eating_habits      食事習慣
 * @property string $sleep               睡眠
 * @property string $stress_factors      ストレス要因
 * @property string $exercise_history    運動歴
 * @property string $medical_history     既往歴
 * @property string $other_notes         その他注意点
 * @property \Cake\I18n\FrozenTime|null $created 作成日時
 * @property \Cake\I18n\FrozenTime|null $modified 更新日時
 *
 * @property \App\Model\Entity\User $user ユーザー関連データ
 */
class Habit extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'occupation' => true,
        'eating_habits' => true,
        'sleep' => true,
        'stress_factors' => true,
        'exercise_history' => true,
        'medical_history' => true,
        'other_notes' => true,
        'created' => true,
        'modified' => true,
    ];
}
