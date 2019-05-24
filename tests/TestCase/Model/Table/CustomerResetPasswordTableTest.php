<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerResetPasswordTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerResetPasswordTable Test Case
 */
class CustomerResetPasswordTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerResetPasswordTable
     */
    public $CustomerResetPassword;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CustomerResetPassword',
        'app.Customers',
        'app.Sessions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CustomerResetPassword') ? [] : ['className' => CustomerResetPasswordTable::class];
        $this->CustomerResetPassword = TableRegistry::getTableLocator()->get('CustomerResetPassword', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerResetPassword);

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
