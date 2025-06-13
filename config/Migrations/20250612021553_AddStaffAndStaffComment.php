<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddStaffAndStaffComment extends AbstractMigration
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
        $table = $this->table('daily_reports');
        $table->addColumn('staff_id', 'integer', [
            'null' => true,
        ]);
        $table->addColumn('staff_comment', 'text', [
            'null' => true,
        ]);
        $table->update();
    }
}
