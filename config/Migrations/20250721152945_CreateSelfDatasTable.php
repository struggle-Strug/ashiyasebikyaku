<?php
use Migrations\AbstractMigration;

class CreateSelfDatasTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('self_datas');
        $table->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('date', 'date', ['null' => true])  // ✅ allow null date
            ->addColumn('good_changes', 'text', ['null' => true])
            ->addColumn('work_review', 'text', ['null' => true])
            ->addColumn('no_change_parts', 'text', ['null' => true])
            ->addColumn('current_state', 'text', ['null' => true])
            ->addColumn('next_two_weeks_work', 'text', ['null' => true])
            ->addColumn('consult_topics', 'text', ['null' => true])
            ->addColumn('consult_content', 'text', ['null' => true])
            ->addTimestamps('created', 'modified')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
