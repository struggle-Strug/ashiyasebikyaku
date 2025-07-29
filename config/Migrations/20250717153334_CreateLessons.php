<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLessons extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('lessons', ['comment' => 'レッスン管理テーブル']);
        $table->addColumn('start_date', 'date', [
                'null' => false,
            ])
            ->addColumn('category', 'string', [
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('staff_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('applicant_name', 'string', [
                'limit' => 100,
                'null' => true,
                'default' => null,
            ])
            ->addColumn('consult_history', 'string', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'null' => true,
            ])
            ->create();
    }
}
