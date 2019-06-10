<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShareStatisticsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShareStatisticsTable Test Case
 */
class ShareStatisticsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShareStatisticsTable
     */
    public $ShareStatistics;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShareStatistics',
        'app.Products',
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
        $config = TableRegistry::getTableLocator()->exists('ShareStatistics') ? [] : ['className' => ShareStatisticsTable::class];
        $this->ShareStatistics = TableRegistry::getTableLocator()->get('ShareStatistics', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShareStatistics);

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
