<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InformationsFixture
 */
class InformationsFixture extends TestFixture
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
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'target' => 'L',
                'start_datetime' => '2023-06-24 09:32:11',
                'end_datetime' => '2023-06-24 09:32:11',
                'created' => '2023-06-24 09:32:11',
                'modified' => '2023-06-24 09:32:11',
                'is_deleted' => 1,
            ],
        ];
        parent::init();
    }
}
