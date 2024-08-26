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
     * Table name
     *
     * @var string
     */
    public $table = 'lesson_movies';
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
                'image_url' => 'Lorem ipsum dolor sit amet',
                'movie_url' => 'Lorem ipsum dolor sit amet',
                'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2024-08-25 16:29:45',
                'modified' => '2024-08-25 16:29:45',
                'is_deleted' => 1,
            ],
        ];
        parent::init();
    }
}
