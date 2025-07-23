<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BodyDatum Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $date
 * @property string|null $ankle
 * @property string|null $instep
 * @property string|null $vertical_arch
 * @property string|null $horizontal_arch
 * @property string|null $toes
 * @property string|null $note
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class BodyData extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'date' => true,
        'ankle' => true,
        'instep' => true,
        'vertical_arch' => true,
        'horizontal_arch' => true,
        'toes' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
    ];
}
