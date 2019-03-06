<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChatDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChatDetailsTable Test Case
 */
class ChatDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChatDetailsTable
     */
    public $ChatDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ChatDetails',
        'app.Chats',
        'app.Customers',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ChatDetails') ? [] : ['className' => ChatDetailsTable::class];
        $this->ChatDetails = TableRegistry::getTableLocator()->get('ChatDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChatDetails);

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
