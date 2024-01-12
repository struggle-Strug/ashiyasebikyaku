<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserPoint Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $point_1
 * @property int $point_2
 * @property int $point_3
 * @property int $point_4
 * @property int $point_5
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class UserPoint extends Entity
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
        'point_1' => true,
        'point_2' => true,
        'point_3' => true,
        'point_4' => true,
        'point_5' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
