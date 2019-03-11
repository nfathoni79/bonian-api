<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerCartDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerCartDetailsTable Test Case
 */
class CustomerCartDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerCartDetailsTable
     */
    public $CustomerCartDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerCartDetails',
        'app.CustomerCarts',
        'app.Products',
        'app.ProductOptionPrices',
        'app.ProductOptionStocks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerCartDetails') ? [] : ['className' => CustomerCartDetailsTable::class];
        $this->CustomerCartDetails = TableRegistry::getTableLocator()->get('CustomerCartDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerCartDetails);

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
     * Test checkStock method
     *
     * @return void
     */
    public function testCheckStock()
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
