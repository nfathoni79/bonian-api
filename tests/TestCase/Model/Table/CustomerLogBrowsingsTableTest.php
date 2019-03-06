<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerLogBrowsingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerLogBrowsingsTable Test Case
 */
class CustomerLogBrowsingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerLogBrowsingsTable
     */
    public $CustomerLogBrowsings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerLogBrowsings',
        'app.Customers',
        'app.Products',
        'app.ProductCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerLogBrowsings') ? [] : ['className' => CustomerLogBrowsingsTable::class];
        $this->CustomerLogBrowsings = TableRegistry::getTableLocator()->get('CustomerLogBrowsings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerLogBrowsings);

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
