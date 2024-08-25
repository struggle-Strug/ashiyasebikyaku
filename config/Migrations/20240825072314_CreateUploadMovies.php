<?php
use Migrations\AbstractMigration;

class CreateUploadMovies extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('upload_movies');
        $table->addColumn('name', 'string', ['limit' => 255])
              ->addColumn('movie_url', 'string', ['limit' => 255])
              ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('is_deleted', 'boolean', ['default' => false])
              ->addPrimaryKey('id')
              ->create();
    }
}
