<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateHabitsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('habits', ['comment' => '生活習慣データ']);
        $table->addColumn('user_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('occupation', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('eating_habits', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('sleep', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('stress_factors', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('exercise_history', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('medical_history', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('other_notes', 'string', [
                'limit' => 255,
                'null' => true,
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
            ->addForeignKey('user_id', 'users', 'id', [
                'delete'=> 'CASCADE',
                'update'=> 'NO_ACTION',
                'constraint' => 'fk_habits_user',
            ])
            ->create();
    }
}
