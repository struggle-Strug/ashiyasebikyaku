<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CourseMovie Entity
 *
 * @property int $id
 * @property int $sort
 * @property int|null $course_id
 * @property string $name
 * @property string|null $image_url
 * @property string $movie_url
 * @property string|null $info_movie_url
 * @property string|null $note
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $is_deleted
 *
 * @property \App\Model\Entity\Course $course
 */
class CourseMovie extends Entity
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
        'sort' => true,
        'course_id' => true,
        'name' => true,
        'image_url' => true,
        'movie_url' => true,
        'info_movie_url' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'is_deleted' => true,
        'course' => true,
    ];
}
