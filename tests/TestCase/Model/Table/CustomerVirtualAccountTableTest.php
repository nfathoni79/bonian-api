<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerVirtualAccountTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerVirtualAccountTable Test Case
 */
class CustomerVirtualAccountTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerVirtualAccountTable
     */
    public $CustomerVirtualAccount;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerVirtualAccount',
        'app.Customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerVirtualAccount') ? [] : ['className' => CustomerVirtualAccountTable::class];
        $this->CustomerVirtualAccount = TableRegistry::getTableLocator()->get('CustomerVirtualAccount', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerVirtualAccount);

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
