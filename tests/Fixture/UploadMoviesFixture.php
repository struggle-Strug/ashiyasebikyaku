<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UploadMoviesFixture
 */
class UploadMoviesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'movie_url' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-06-24 00:16:39',
                'is_deleted' => 1,
            ],
        ];
        parent::init();
    }
}
