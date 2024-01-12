<?php
declare(strict_types=1);

namespace App\Model\Entity;
use Cake\Auth\DefaultPasswordHasher;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $member_number
 * @property string $uuid
 * @property string $email
 * @property string $password
 * @property string $last_name
 * @property string $first_name
 * @property string|null $last_name_kana
 * @property string|null $first_name_kana
 * @property string|null $zipcode
 * @property string|null $prefecture
 * @property string|null $city
 * @property string|null $address
 * @property string|null $tel
 * @property \Cake\I18n\FrozenDate|null $birthday
 * @property string|null $sextype
 * @property int $is_continue
 * @property \Cake\I18n\FrozenTime $start_datetime
 * @property \Cake\I18n\FrozenTime|null $end_datetime
 * @property int|null $staff_id
 * @property string|null $payment
 * @property string|null $sales_history
 * @property string|null $note
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $is_deleted
 *
 * @property \App\Model\Entity\Staff $staff
 * @property \App\Model\Entity\DailyReport[] $daily_reports
 * @property \App\Model\Entity\WeeklyReport[] $weekly_reports
 */
class User extends Entity
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
        'member_number' => true,
        'uuid' => true,
        'email' => true,
        'password' => true,
        'last_name' => true,
        'first_name' => true,
        'last_name_kana' => true,
        'first_name_kana' => true,
        'zipcode' => true,
        'prefecture' => true,
        'city' => true,
        'address' => true,
        'tel' => true,
        'birthday' => true,
        'sextype' => true,
        'is_continue' => true,
        'start_datetime' => true,
        'end_datetime' => true,
        'staff_id' => true,
        'staff_message' => true,
        'staff_week_message' => true,
        'payment' => true,
        'sales_history' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'is_deleted' => true,
        'staff' => true,
        'daily_reports' => true,
        'weekly_reports' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }

    protected function _getFullname()
    {
        return $this->_fields['last_name'] . ' ' . $this->_fields['first_name'];
    }
}
