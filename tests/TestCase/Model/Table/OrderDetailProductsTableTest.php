<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrderDetailProductsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrderDetailProductsTable Test Case
 */
class OrderDetailProductsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrderDetailProductsTable
     */
    public $OrderDetailProducts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OrderDetailProducts',
        'app.OrderDetails',
        'app.Products',
        'app.OptionValues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OrderDetailProducts') ? [] : ['className' => OrderDetailProductsTable::class];
        $this->OrderDetailProducts = TableRegistry::getTableLocator()->get('OrderDetailProducts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrderDetailProducts);

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
