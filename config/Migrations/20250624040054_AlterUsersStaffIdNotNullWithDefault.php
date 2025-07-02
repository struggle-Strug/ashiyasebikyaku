<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterUsersStaffIdNotNullWithDefault extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $this->execute('UPDATE users SET staff_id = 4 WHERE staff_id IS NULL');

        $this->table('users')
            ->changeColumn('staff_id', 'integer', [
                'null' => false,
                'default' => 4, // SHOCO先生の staff_id
            ])
            ->update();
    }
}
