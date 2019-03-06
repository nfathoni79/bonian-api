<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerGroupsTable Test Case
 */
class CustomerGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerGroupsTable
     */
    public $CustomerGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerGroups',
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
        $config = TableRegistry::getTableLocator()->exists('CustomerGroups') ? [] : ['className' => CustomerGroupsTable::class];
        $this->CustomerGroups = TableRegistry::getTableLocator()->get('CustomerGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerGroups);

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
}
