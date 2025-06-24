<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCategories extends AbstractMigration
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
        $this->table('categories')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addTimestamps('created', 'updated') // This adds 'created' and 'updated'
            ->create();
    }

}
