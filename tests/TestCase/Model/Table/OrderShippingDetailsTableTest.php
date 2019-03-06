<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrderShippingDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrderShippingDetailsTable Test Case
 */
class OrderShippingDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrderShippingDetailsTable
     */
    public $OrderShippingDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OrderShippingDetails',
        'app.OrderDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OrderShippingDetails') ? [] : ['className' => OrderShippingDetailsTable::class];
        $this->OrderShippingDetails = TableRegistry::getTableLocator()->get('OrderShippingDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrderShippingDetails);

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
