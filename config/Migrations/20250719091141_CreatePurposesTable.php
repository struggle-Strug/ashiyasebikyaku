<?php
use Migrations\AbstractMigration;

class CreatePurposesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('purposes');
        $table->addColumn('user_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('email', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('current_concerns_appearance', 'text', [
            'null' => true,
        ])
        ->addColumn('current_concerns_function', 'text', [
            'null' => true,
        ])
        ->addColumn('goal_appearance', 'text', [
            'null' => true,
        ])
        ->addColumn('goal_function', 'text', [
            'null' => true,
        ])
        ->addColumn('future_self_image', 'text', [
            'null' => true,
        ])
        ->addColumn('next_two_weeks_work', 'text', [
            'null' => true,
        ])
        ->addColumn('consulting_topics', 'text', [
            'null' => true,
        ])
        ->addTimestamps('created', 'modified') // Automatically add created and modified timestamps
        ->addForeignKey('user_id', 'users', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
