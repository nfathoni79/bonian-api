<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerDigitalInquiryTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerDigitalInquiryTable Test Case
 */
class CustomerDigitalInquiryTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerDigitalInquiryTable
     */
    public $CustomerDigitalInquiry;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerDigitalInquiry',
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
        $config = TableRegistry::getTableLocator()->exists('CustomerDigitalInquiry') ? [] : ['className' => CustomerDigitalInquiryTable::class];
        $this->CustomerDigitalInquiry = TableRegistry::getTableLocator()->get('CustomerDigitalInquiry', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerDigitalInquiry);

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
