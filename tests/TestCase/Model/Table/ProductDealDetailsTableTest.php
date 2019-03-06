<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductDealDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductDealDetailsTable Test Case
 */
class ProductDealDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductDealDetailsTable
     */
    public $ProductDealDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductDealDetails',
        'app.ProductDeals',
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
        $config = TableRegistry::getTableLocator()->exists('ProductDealDetails') ? [] : ['className' => ProductDealDetailsTable::class];
        $this->ProductDealDetails = TableRegistry::getTableLocator()->get('ProductDealDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductDealDetails);

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
