<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddAdmincommentToCompareImages extends AbstractMigration
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
        $table = $this->table('compared_images');
        $table->addColumn('admin_comment', 'text', [
            'default' => '',
            'null' => false,
        ]);
        $table->addColumn('staff_comment', 'text', [
            'default' => '',
            'null' => false,
        ]);
        $table->addColumn('staff_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
