<?php
use Migrations\AbstractMigration;

class CreateConsultsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('consults');
        $table->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('date', 'date', ['null' => true])
            ->addColumn('category', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('theme', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('instruction', 'text', ['null' => true])
            ->addColumn('note', 'text', ['null' => true])
            ->addColumn('next_step', 'text', ['null' => true])
            ->addColumn('staff_id', 'integer', ['null' => true])
            ->addTimestamps('created', 'modified')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('staff_id', 'staffs', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
