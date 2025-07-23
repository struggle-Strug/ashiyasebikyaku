<?php
use Migrations\AbstractMigration;

class CreateBodyDatasTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('body_datas');
        $table->addColumn('user_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('date', 'date', [
                'null' => true,
            ])
            ->addColumn('ankle', 'string', [
                'null' => true,
            ])
            ->addColumn('instep', 'string', [
                'null' => true,
            ])
            ->addColumn('vertical_arch', 'string', [
                'null' => true,
            ])
            ->addColumn('horizontal_arch', 'string', [
                'null' => true,
            ])
            ->addColumn('toes', 'string', [
                'null' => true,
            ])
            ->addColumn('note', 'text', [
                'null' => true,
            ])
            ->addTimestamps('created', 'modified')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
