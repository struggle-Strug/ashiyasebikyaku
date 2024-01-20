<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class StaffWeeklyMessagesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('staff_weekly_messages');
        $this->setPrimaryKey('id');
        // 他の初期化コード（リレーション、ビヘイビアなど）
    }
}
