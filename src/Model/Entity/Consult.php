<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Consult Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate|null $date
 * @property string|null $category
 * @property string|null $theme
 * @property string|null $instruction
 * @property string|null $note
 * @property string|null $next_step
 * @property int|null $staff_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Staff $staff
 */
class Consult extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'date' => true,
        'category' => true,
        'theme' => true,
        'instruction' => true,
        'note' => true,
        'next_step' => true,
        'staff_id' => true,
        'created' => true,
        'modified' => true,
    ];
}
