<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductOptionPricesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductOptionPricesTable Test Case
 */
class ProductOptionPricesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductOptionPricesTable
     */
    public $ProductOptionPrices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductOptionPrices',
        'app.Products',
        'app.PriceSettingDetails',
        'app.ProductOptionStocks',
        'app.ProductOptionValueLists'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductOptionPrices') ? [] : ['className' => ProductOptionPricesTable::class];
        $this->ProductOptionPrices = TableRegistry::getTableLocator()->get('ProductOptionPrices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductOptionPrices);

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
