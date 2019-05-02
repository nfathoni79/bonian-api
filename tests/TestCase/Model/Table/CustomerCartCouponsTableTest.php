<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerCartCouponsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerCartCouponsTable Test Case
 */
class CustomerCartCouponsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerCartCouponsTable
     */
    public $CustomerCartCoupons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerCartCoupons',
        'app.CustomerCarts',
        'app.ProductCoupons'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerCartCoupons') ? [] : ['className' => CustomerCartCouponsTable::class];
        $this->CustomerCartCoupons = TableRegistry::getTableLocator()->get('CustomerCartCoupons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerCartCoupons);

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
