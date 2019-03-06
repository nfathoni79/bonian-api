<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductStockStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductStockStatusesTable Test Case
 */
class ProductStockStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductStockStatusesTable
     */
    public $ProductStockStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductStockStatuses',
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
        $config = TableRegistry::getTableLocator()->exists('ProductStockStatuses') ? [] : ['className' => ProductStockStatusesTable::class];
        $this->ProductStockStatuses = TableRegistry::getTableLocator()->get('ProductStockStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductStockStatuses);

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
