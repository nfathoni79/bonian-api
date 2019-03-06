<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmailQueueTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmailQueueTable Test Case
 */
class EmailQueueTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmailQueueTable
     */
    public $EmailQueue;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EmailQueue'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EmailQueue') ? [] : ['className' => EmailQueueTable::class];
        $this->EmailQueue = TableRegistry::getTableLocator()->get('EmailQueue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmailQueue);

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
