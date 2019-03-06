<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerStatusesTable Test Case
 */
class CustomerStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerStatusesTable
     */
    public $CustomerStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerStatuses',
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
        $config = TableRegistry::getTableLocator()->exists('CustomerStatuses') ? [] : ['className' => CustomerStatusesTable::class];
        $this->CustomerStatuses = TableRegistry::getTableLocator()->get('CustomerStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerStatuses);

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
