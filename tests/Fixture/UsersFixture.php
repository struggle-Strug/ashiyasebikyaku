<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'member_number' => 'Lorem ipsum dolor sit amet',
                'uuid' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name_kana' => 'Lorem ipsum dolor sit amet',
                'first_name_kana' => 'Lorem ipsum dolor sit amet',
                'zipcode' => 'Lorem ip',
                'prefecture' => 'Lorem ip',
                'city' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet',
                'tel' => 'Lorem ipsum dolor ',
                'birthday' => '2023-06-24',
                'sextype' => 'L',
                'is_continue' => 1,
                'start_datetime' => '2023-06-24 00:16:05',
                'end_datetime' => '2023-06-24 00:16:05',
                'staff_id' => 1,
                'payment' => 'Lorem ipsum dolor sit amet',
                'sales_history' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2023-06-24 00:16:05',
                'modified' => '2023-06-24 00:16:05',
                'is_deleted' => 1,
            ],
        ];
        parent::init();
    }
}
