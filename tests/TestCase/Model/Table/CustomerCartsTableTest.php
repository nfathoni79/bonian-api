<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerCartsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerCartsTable Test Case
 */
class CustomerCartsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerCartsTable
     */
    public $CustomerCarts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerCarts',
        'app.Customers',
        'app.CustomerCartDetails',
        'app.CustomerCartCoupons'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerCarts') ? [] : ['className' => CustomerCartsTable::class];
        $this->CustomerCarts = TableRegistry::getTableLocator()->get('CustomerCarts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerCarts);

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
