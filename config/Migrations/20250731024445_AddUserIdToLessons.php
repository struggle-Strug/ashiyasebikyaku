<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddUserIdToLessons extends AbstractMigration
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
        $table = $this->table('lessons');
        $table
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->update();
        $table
            ->addForeignKey('user_id', 'users', 'id', [
                'delete'=> 'CASCADE',
                'update'=> 'NO_ACTION',
            ])
            ->update();
    }
}
