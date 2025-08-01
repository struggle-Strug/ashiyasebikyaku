<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SelfData Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $good_changes
 * @property string|null $work_review
 * @property string|null $no_change_parts
 * @property string|null $current_state
 * @property string|null $next_two_weeks_work
 * @property string|null $consult_topics
 * @property string|null $consult_content
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class SelfDatum extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'date' => true,
        'good_changes' => true,
        'work_review' => true,
        'no_change_parts' => true,
        'current_state' => true,
        'next_two_weeks_work' => true,
        'consult_topics' => true,
        'consult_content' => true,
        'created' => true,
        'modified' => true,
    ];
}
