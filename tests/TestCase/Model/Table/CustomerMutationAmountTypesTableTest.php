<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerMutationAmountTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerMutationAmountTypesTable Test Case
 */
class CustomerMutationAmountTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerMutationAmountTypesTable
     */
    public $CustomerMutationAmountTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerMutationAmountTypes',
        'app.CustomerMutationAmounts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerMutationAmountTypes') ? [] : ['className' => CustomerMutationAmountTypesTable::class];
        $this->CustomerMutationAmountTypes = TableRegistry::getTableLocator()->get('CustomerMutationAmountTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerMutationAmountTypes);

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
