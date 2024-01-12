<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WeeklyReport Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $report_date
 * @property string|null $weight
 * @property string|null $fat_percent
 * @property string|null $size1
 * @property string|null $size2
 * @property string|null $size3
 * @property string|null $size4
 * @property string|null $size5
 * @property string|null $size6
 * @property string|null $size7
 * @property string|null $size8
 * @property string|null $size9
 * @property int $is_draft
 * @property int $is_checked
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Photo[] $photos
 */
class WeeklyReport extends Entity
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
        'weight' => true,
        'fat_percent' => true,
        'size1' => true,
        'size2' => true,
        'size3' => true,
        'size4' => true,
        'size5' => true,
        'size6' => true,
        'size7' => true,
        'size8' => true,
        'size9' => true,
        'is_draft' => true,
        'is_checked' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'photos' => true,
    ];
}
