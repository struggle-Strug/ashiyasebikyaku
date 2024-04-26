<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ComparedImage Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $image
 * @property int|null $target_image
 * @property int|null $subject_image
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 * @property string|null $comment
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Photo $photo
 */
class ComparedImage extends Entity
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
        'image' => true,
        'target_image' => true,
        'subject_image' => true,
        'created' => true,
        'updated' => true,
        'comment' => true,
        'user' => true,
        'photo' => true,
    ];
}
