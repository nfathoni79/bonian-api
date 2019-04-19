<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BrowsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BrowsersTable Test Case
 */
class BrowsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BrowsersTable
     */
    public $Browsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Browsers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Browsers') ? [] : ['className' => BrowsersTable::class];
        $this->Browsers = TableRegistry::getTableLocator()->get('Browsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Browsers);

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
