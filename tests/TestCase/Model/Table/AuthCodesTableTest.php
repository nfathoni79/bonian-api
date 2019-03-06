<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuthCodesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuthCodesTable Test Case
 */
class AuthCodesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AuthCodesTable
     */
    public $AuthCodes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AuthCodes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AuthCodes') ? [] : ['className' => AuthCodesTable::class];
        $this->AuthCodes = TableRegistry::getTableLocator()->get('AuthCodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AuthCodes);

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
