<?php
use Migrations\AbstractMigration;

class AlterPurposesEmailNameNullable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('purposes');
        $table->changeColumn('email', 'string', ['limit' => 255, 'null' => true])
              ->changeColumn('name', 'string', ['limit' => 255, 'null' => true])
              ->update();
    }
}
