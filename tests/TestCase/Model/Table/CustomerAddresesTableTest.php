<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerAddresesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerAddresesTable Test Case
 */
class CustomerAddresesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerAddresesTable
     */
    public $CustomerAddreses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerAddreses',
        'app.Customers',
        'app.Provinces',
        'app.Cities',
        'app.Subdistricts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerAddreses') ? [] : ['className' => CustomerAddresesTable::class];
        $this->CustomerAddreses = TableRegistry::getTableLocator()->get('CustomerAddreses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerAddreses);

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
