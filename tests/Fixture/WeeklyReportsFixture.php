<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WeeklyReportsFixture
 */
class WeeklyReportsFixture extends TestFixture
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
                'user_id' => 1,
                'report_date' => '2023-06-25',
                'weight' => 'Lorem ip',
                'fat_percent' => 'Lorem ip',
                'size1' => 'Lorem ip',
                'size2' => 'Lorem ip',
                'size3' => 'Lorem ip',
                'size4' => 'Lorem ip',
                'size5' => 'Lorem ip',
                'size6' => 'Lorem ip',
                'size7' => 'Lorem ip',
                'size8' => 'Lorem ip',
                'size9' => 'Lorem ip',
                'is_draft' => 1,
                'is_checked' => 1,
                'created' => '2023-06-25 14:30:13',
                'modified' => '2023-06-25 14:30:13',
            ],
        ];
        parent::init();
    }
}
