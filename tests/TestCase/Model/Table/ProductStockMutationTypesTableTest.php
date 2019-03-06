<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductStockMutationTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductStockMutationTypesTable Test Case
 */
class ProductStockMutationTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductStockMutationTypesTable
     */
    public $ProductStockMutationTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductStockMutationTypes',
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
        $config = TableRegistry::getTableLocator()->exists('ProductStockMutationTypes') ? [] : ['className' => ProductStockMutationTypesTable::class];
        $this->ProductStockMutationTypes = TableRegistry::getTableLocator()->get('ProductStockMutationTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductStockMutationTypes);

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
