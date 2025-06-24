<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFileFieldsToMessages extends AbstractMigration
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
        $table->addColumn('file_path', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
        ]);
        $table->addColumn('file_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('file_type', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        $table->addColumn('file_size', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
