<?php
use Migrations\AbstractMigration;

class CreateDifferencesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('differences');
        $table->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('milestone', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('category', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('target1', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('target2', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('evaluation1', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('evaluation2', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('comment1', 'text', ['null' => true])
            ->addColumn('comment2', 'text', ['null' => true])
            ->addTimestamps('created', 'modified')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
