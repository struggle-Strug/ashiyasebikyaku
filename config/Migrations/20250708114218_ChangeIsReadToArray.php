<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeIsReadToArray extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('messages');
        $table->changeColumn('is_read', 'json', [
            'default' => '[]', // ✅ valid JSON for empty array
            'null' => true,
        ]);
        $table->update();
    }
}
