<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LessonMoviesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LessonMoviesTable Test Case
 */
class LessonMoviesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LessonMoviesTable
     */
    protected $LessonMovies;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.LessonMovies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LessonMovies') ? [] : ['className' => LessonMoviesTable::class];
        $this->LessonMovies = $this->getTableLocator()->get('LessonMovies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LessonMovies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LessonMoviesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
