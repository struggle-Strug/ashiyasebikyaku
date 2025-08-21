<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddForeignKeyStaffIdToLessons extends AbstractMigration
{
    public function change()
    {
        $this->table('lessons')
            ->addForeignKey('staff_id', 'staffs', 'id', [
                'delete'=> 'CASCADE',
                'update'=> 'NO_ACTION',
                'constraint' => 'fk_lessons_staff_id',
            ])
            ->update();
    }
}
