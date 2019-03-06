<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerMutationPointTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerMutationPointTypesTable Test Case
 */
class CustomerMutationPointTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerMutationPointTypesTable
     */
    public $CustomerMutationPointTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerMutationPointTypes',
        'app.CustomerMutationPoints'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerMutationPointTypes') ? [] : ['className' => CustomerMutationPointTypesTable::class];
        $this->CustomerMutationPointTypes = TableRegistry::getTableLocator()->get('CustomerMutationPointTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerMutationPointTypes);

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
