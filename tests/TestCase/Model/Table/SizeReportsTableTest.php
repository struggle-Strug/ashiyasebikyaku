<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SizeReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SizeReportsTable Test Case
 */
class SizeReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SizeReportsTable
     */
    protected $SizeReports;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SizeReports',
        'app.WeeklyReports',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SizeReports') ? [] : ['className' => SizeReportsTable::class];
        $this->SizeReports = $this->getTableLocator()->get('SizeReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SizeReports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SizeReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SizeReportsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
