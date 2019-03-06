<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductDealsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductDealsTable Test Case
 */
class ProductDealsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductDealsTable
     */
    public $ProductDeals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductDeals',
        'app.ProductDealDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductDeals') ? [] : ['className' => ProductDealsTable::class];
        $this->ProductDeals = TableRegistry::getTableLocator()->get('ProductDeals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductDeals);

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
