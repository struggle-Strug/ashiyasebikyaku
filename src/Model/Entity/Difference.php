<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Difference Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $milestone
 * @property string|null $category
 * @property string|null $target1
 * @property string|null $target2
 * @property string|null $evaluation1
 * @property string|null $evaluation2
 * @property string|null $comment1
 * @property string|null $comment2
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Difference extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'milestone' => true,
        'category' => true,
        'target1' => true,
        'target2' => true,
        'evaluation1' => true,
        'evaluation2' => true,
        'comment1' => true,
        'comment2' => true,
        'created' => true,
        'modified' => true,
    ];
}
