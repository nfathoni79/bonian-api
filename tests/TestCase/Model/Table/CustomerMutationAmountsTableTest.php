<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerMutationAmountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerMutationAmountsTable Test Case
 */
class CustomerMutationAmountsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerMutationAmountsTable
     */
    public $CustomerMutationAmounts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerMutationAmounts',
        'app.Customers',
        'app.CustomerMutationAmountTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerMutationAmounts') ? [] : ['className' => CustomerMutationAmountsTable::class];
        $this->CustomerMutationAmounts = TableRegistry::getTableLocator()->get('CustomerMutationAmounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerMutationAmounts);

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
