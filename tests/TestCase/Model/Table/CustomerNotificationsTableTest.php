<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerNotificationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerNotificationsTable Test Case
 */
class CustomerNotificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerNotificationsTable
     */
    public $CustomerNotifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerNotifications',
        'app.Customers',
        'app.CustomerNotificationTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerNotifications') ? [] : ['className' => CustomerNotificationsTable::class];
        $this->CustomerNotifications = TableRegistry::getTableLocator()->get('CustomerNotifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerNotifications);

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
