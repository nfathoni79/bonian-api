<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SearchStatsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SearchStatsTable Test Case
 */
class SearchStatsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SearchStatsTable
     */
    public $SearchStats;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SearchStats',
        'app.SearchTerms',
        'app.Browsers',
        'app.Customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SearchStats') ? [] : ['className' => SearchStatsTable::class];
        $this->SearchStats = TableRegistry::getTableLocator()->get('SearchStats', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SearchStats);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
