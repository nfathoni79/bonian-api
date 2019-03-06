<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductOptionStocksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductOptionStocksTable Test Case
 */
class ProductOptionStocksTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductOptionStocksTable
     */
    public $ProductOptionStocks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductOptionStocks',
        'app.Products',
        'app.ProductOptionPrices',
        'app.Branches',
        'app.ProductStockMutations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductOptionStocks') ? [] : ['className' => ProductOptionStocksTable::class];
        $this->ProductOptionStocks = TableRegistry::getTableLocator()->get('ProductOptionStocks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductOptionStocks);

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
