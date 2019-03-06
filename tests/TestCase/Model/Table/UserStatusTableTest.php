<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserStatusTable Test Case
 */
class UserStatusTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserStatusTable
     */
    public $UserStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UserStatus',
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
        $config = TableRegistry::getTableLocator()->exists('UserStatus') ? [] : ['className' => UserStatusTable::class];
        $this->UserStatus = TableRegistry::getTableLocator()->get('UserStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserStatus);

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
