<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourseMoviesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseMoviesTable Test Case
 */
class CourseMoviesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseMoviesTable
     */
    protected $CourseMovies;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CourseMovies',
        'app.Courses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CourseMovies') ? [] : ['className' => CourseMoviesTable::class];
        $this->CourseMovies = $this->getTableLocator()->get('CourseMovies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CourseMovies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CourseMoviesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CourseMoviesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
