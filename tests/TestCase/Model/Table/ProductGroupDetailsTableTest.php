<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductGroupDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductGroupDetailsTable Test Case
 */
class ProductGroupDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductGroupDetailsTable
     */
    public $ProductGroupDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductGroupDetails',
        'app.ProductGroups',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductGroupDetails') ? [] : ['className' => ProductGroupDetailsTable::class];
        $this->ProductGroupDetails = TableRegistry::getTableLocator()->get('ProductGroupDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductGroupDetails);

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
