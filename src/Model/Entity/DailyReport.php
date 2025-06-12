<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DailyReport Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $report_date
 * @property string $status
 * @property string|null $intestine_status
 * @property string|null $comment
 * @property int $is_checked
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class DailyReport extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'user_id' => true,
        'report_date' => true,
        'status' => true,
        'intestine_status' => true,
        'comment' => true,
        'is_checked' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'staff_id' => true,
        'staff_comment' => true,
    ];
}
