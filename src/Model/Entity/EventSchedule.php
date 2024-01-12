<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EventSchedule Entity
 *
 * @property int $id
 * @property int $event_id
 * @property \Cake\I18n\FrozenDate $event_date
 * @property string $start_time
 * @property string $end_time
 * @property \Cake\I18n\FrozenTime $cancel_datetime
 * @property int $capacity
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Event $event
 * @property \App\Model\Entity\EventEntry[] $event_entries
 */
class EventSchedule extends Entity
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
        'event_id' => true,
        'event_date' => true,
        'start_time' => true,
        'end_time' => true,
        'cancel_datetime' => true,
        'capacity' => true,
        'created' => true,
        'modified' => true,
        'event' => true,
        'event_entries' => true,
    ];
}
