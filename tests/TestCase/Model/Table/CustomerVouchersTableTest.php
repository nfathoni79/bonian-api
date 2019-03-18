<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerVouchersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerVouchersTable Test Case
 */
class CustomerVouchersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerVouchersTable
     */
    public $CustomerVouchers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerVouchers',
        'app.Customers',
        'app.Vouchers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerVouchers') ? [] : ['className' => CustomerVouchersTable::class];
        $this->CustomerVouchers = TableRegistry::getTableLocator()->get('CustomerVouchers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerVouchers);

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
