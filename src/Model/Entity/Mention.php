<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mention Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $personality_tendency
 * @property string|null $motivation_source
 * @property string|null $dropout_risk
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Mention extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'personality_tendency' => true,
        'motivation_source' => true,
        'dropout_risk' => true,
        'created' => true,
        'modified' => true,
    ];
}
