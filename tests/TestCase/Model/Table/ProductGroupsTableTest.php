<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductGroupsTable Test Case
 */
class ProductGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductGroupsTable
     */
    public $ProductGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductGroups',
        'app.CustomerBuyGroups',
        'app.ProductGroupDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductGroups') ? [] : ['className' => ProductGroupsTable::class];
        $this->ProductGroups = TableRegistry::getTableLocator()->get('ProductGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductGroups);

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
