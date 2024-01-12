<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PhotosFixture
 */
class PhotosFixture extends TestFixture
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
                'weekly_report_id' => 1,
                'photo_number' => 1,
                'image_url' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-06-25 15:34:20',
                'modified' => '2023-06-25 15:34:20',
            ],
        ];
        parent::init();
    }
}
