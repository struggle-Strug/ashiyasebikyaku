<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lesson Entity
 *
 * @property int $id                  レッスンID (primary key)
 * @property \Cake\I18n\FrozenDate $start_data 開催日
 * @property string $category         区分 (例: 実践レッスン, 脳バグレッスン)
 * @property int $staff_id       担当講師ID（Staffsテーブル参照）
 * @property string|null $applicant_name 申込者名（または「･･･」などの表示用）
 * @property string|null 指導履歴
 * @property \Cake\I18n\FrozenTime|null $created 作成日時
 * @property \Cake\I18n\FrozenTime|null $modified 更新日時
 *
 */
class Lesson extends Entity
{
    protected $_accessible = [
        'start_date' => true,
        'user_id' => true,
        'category' => true,
        'staff_id' => true,
        'applicant_name' => true,
        'consult_history' => true,
        'created' => true,
        'modified' => true,
    ];
}
