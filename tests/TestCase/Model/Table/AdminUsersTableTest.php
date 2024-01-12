<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdminUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdminUsersTable Test Case
 */
class AdminUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AdminUsersTable
     */
    protected $AdminUsers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.AdminUsers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AdminUsers') ? [] : ['className' => AdminUsersTable::class];
        $this->AdminUsers = $this->getTableLocator()->get('AdminUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AdminUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AdminUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
