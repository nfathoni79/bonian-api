<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductStockMutationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductStockMutationsTable Test Case
 */
class ProductStockMutationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductStockMutationsTable
     */
    public $ProductStockMutations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductStockMutations',
        'app.Products',
        'app.Branches',
        'app.ProductOptionStocks',
        'app.ProductStockMutationTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductStockMutations') ? [] : ['className' => ProductStockMutationsTable::class];
        $this->ProductStockMutations = TableRegistry::getTableLocator()->get('ProductStockMutations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductStockMutations);

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
