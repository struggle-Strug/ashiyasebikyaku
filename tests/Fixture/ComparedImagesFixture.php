<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ComparedImagesFixture
 */
class ComparedImagesFixture extends TestFixture
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
                'image' => 'Lorem ipsum dolor sit amet',
                'target_image' => 1,
                'subject_image' => 1,
                'created' => 1713596840,
                'updated' => 1713596840,
                'comment' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
