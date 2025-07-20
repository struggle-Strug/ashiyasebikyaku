<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Purpose Entity
 *
 * @property int $id                          ID (primary key)
 * @property int $user_id                     ユーザーID (Usersテーブル参照)
 * @property string $email                    メールアドレス
 * @property string $name                     名前
 * @property string $current_concerns_appearance 現在の外見に関する懸念
 * @property string $current_concerns_function 現在の機能に関する懸念
 * @property string $goal_appearance          目標外見
 * @property string $goal_function            目標機能
 * @property string $future_self_image        将来の自分のイメージ
 * @property string $next_two_weeks_work      次の2週間の仕事
 * @property string $consulting_topics        コンサルティングトピック
 * @property \Cake\I18n\FrozenTime|null $created 作成日時
 * @property \Cake\I18n\FrozenTime|null $modified 更新日時
 *
 * @property \App\Model\Entity\User $user      ユーザー関連データ
 */
class Purpose extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'email' => true,
        'name' => true,
        'current_concerns_appearance' => true,
        'current_concerns_function' => true,
        'goal_appearance' => true,
        'goal_function' => true,
        'future_self_image' => true,
        'next_two_weeks_work' => true,
        'consulting_topics' => true,
        'created' => true,
        'modified' => true,
    ];
}
