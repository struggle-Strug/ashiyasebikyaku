<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ImageReportsFixture
 */
class ImageReportsFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'value' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-06-24 00:17:13',
                'modified' => '2023-06-24 00:17:13',
            ],
        ];
        parent::init();
    }
}
