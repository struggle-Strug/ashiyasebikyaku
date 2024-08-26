<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UploadMoviesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UploadMoviesTable Test Case
 */
class UploadMoviesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UploadMoviesTable
     */
    protected $UploadMovies;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.UploadMovies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UploadMovies') ? [] : ['className' => UploadMoviesTable::class];
        $this->UploadMovies = $this->getTableLocator()->get('UploadMovies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UploadMovies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UploadMoviesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
