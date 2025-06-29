<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeLessonPassword extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function up(): void
    {
        // Change default value of password column to empty string
        $this->execute(
            "ALTER TABLE lesson_movies MODIFY password VARCHAR(255) DEFAULT '' NULL"
        );

        // Update existing rows where password is '20250625' to empty string
        $this->execute(
            "UPDATE lesson_movies SET password = '' WHERE password = '20250625'"
        );
    }

    public function down(): void
    {
        // Revert default to '20250625'
        $this->execute(
            "ALTER TABLE lesson_movies MODIFY password VARCHAR(255) DEFAULT '20250625' NULL"
        );

        // Optionally revert rows (only if needed)
        $this->execute(
            "UPDATE lesson_movies SET password = '20250625' WHERE password = ''"
        );
    }
}
