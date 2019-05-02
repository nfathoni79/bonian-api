<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductCouponsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductCouponsTable Test Case
 */
class ProductCouponsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductCouponsTable
     */
    public $ProductCoupons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductCoupons',
        'app.Products',
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
        $config = TableRegistry::getTableLocator()->exists('ProductCoupons') ? [] : ['className' => ProductCouponsTable::class];
        $this->ProductCoupons = TableRegistry::getTableLocator()->get('ProductCoupons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductCoupons);

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
