<?php
use Migrations\AbstractMigration;

class CreateMentionsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('mentions');
        $table->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('personality_tendency', 'text', ['null' => true])
            ->addColumn('motivation_source', 'text', ['null' => true])
            ->addColumn('dropout_risk', 'text', ['null' => true])
            ->addTimestamps('created', 'modified')
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
