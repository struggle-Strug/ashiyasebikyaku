<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property int|null $staff_id
 * @property string $title
 * @property string|null $type
 * @property string $content
 * @property \Cake\I18n\FrozenTime $start_datetime
 * @property \Cake\I18n\FrozenTime|null $end_datetime
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property bool $is_deleted
 *
 * @property \App\Model\Entity\Staff $staff
 * @property \App\Model\Entity\EventEntry[] $event_entries
 * @property \App\Model\Entity\EventSchedule[] $event_schedules
 */
class Event extends Entity
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
        'staff_id' => true,
        'title' => true,
        'type' => true,
        'content' => true,
        'start_datetime' => true,
        'end_datetime' => true,
        'created' => true,
        'modified' => true,
        'is_deleted' => true,
        'staff' => true,
        'event_entries' => true,
        'event_schedules' => true,
    ];
}
