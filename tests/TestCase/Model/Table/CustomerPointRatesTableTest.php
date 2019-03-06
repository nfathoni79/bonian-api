<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerPointRatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerPointRatesTable Test Case
 */
class CustomerPointRatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerPointRatesTable
     */
    public $CustomerPointRates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerPointRates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerPointRates') ? [] : ['className' => CustomerPointRatesTable::class];
        $this->CustomerPointRates = TableRegistry::getTableLocator()->get('CustomerPointRates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerPointRates);

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
