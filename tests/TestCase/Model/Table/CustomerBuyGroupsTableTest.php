<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerBuyGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerBuyGroupsTable Test Case
 */
class CustomerBuyGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerBuyGroupsTable
     */
    public $CustomerBuyGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerBuyGroups',
        'app.ProductGroups',
        'app.Customers',
        'app.CustomerBuyGroupDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerBuyGroups') ? [] : ['className' => CustomerBuyGroupsTable::class];
        $this->CustomerBuyGroups = TableRegistry::getTableLocator()->get('CustomerBuyGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerBuyGroups);

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
