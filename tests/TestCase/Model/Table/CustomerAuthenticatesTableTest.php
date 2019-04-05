<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerAuthenticatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerAuthenticatesTable Test Case
 */
class CustomerAuthenticatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerAuthenticatesTable
     */
    public $CustomerAuthenticates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerAuthenticates',
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
        $config = TableRegistry::getTableLocator()->exists('CustomerAuthenticates') ? [] : ['className' => CustomerAuthenticatesTable::class];
        $this->CustomerAuthenticates = TableRegistry::getTableLocator()->get('CustomerAuthenticates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerAuthenticates);

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
