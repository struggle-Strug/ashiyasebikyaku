<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImageReportsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImageReportsTable Test Case
 */
class ImageReportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ImageReportsTable
     */
    protected $ImageReports;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ImageReports',
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
        $config = $this->getTableLocator()->exists('ImageReports') ? [] : ['className' => ImageReportsTable::class];
        $this->ImageReports = $this->getTableLocator()->get('ImageReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ImageReports);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ImageReportsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\ImageReportsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
