<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddIsAdminReadToMessages extends AbstractMigration
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
        $table = $this->table('messages');
        $table->addColumn('is_admin_read', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
