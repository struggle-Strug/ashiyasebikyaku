<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddCourseToUser extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('course_id', 'integer', [
            'default' => 1,
            'null' => true,
        ]);
        $table->update();
    }
}
