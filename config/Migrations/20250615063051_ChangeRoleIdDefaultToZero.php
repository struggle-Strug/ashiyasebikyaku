<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeRoleIdDefaultToZero extends AbstractMigration
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
        $table = $this->table('admin_users');
        $table->changeColumn('role_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
