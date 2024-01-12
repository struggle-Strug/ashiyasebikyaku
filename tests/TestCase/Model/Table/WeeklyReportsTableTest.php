<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WeeklyReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WeeklyReportsTable Test Case
 */
class WeeklyReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WeeklyReportsTable
     */
    protected $WeeklyReports;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.WeeklyReports',
        'app.Users',
        'app.Photos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('WeeklyReports') ? [] : ['className' => WeeklyReportsTable::class];
        $this->WeeklyReports = $this->getTableLocator()->get('WeeklyReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->WeeklyReports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\WeeklyReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\WeeklyReportsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
