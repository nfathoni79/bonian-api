<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GenerationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GenerationsTable Test Case
 */
class GenerationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GenerationsTable
     */
    public $Generations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Generations',
        'app.Customers',
        'app.Refferals'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Generations') ? [] : ['className' => GenerationsTable::class];
        $this->Generations = TableRegistry::getTableLocator()->get('Generations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Generations);

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
