<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArosAcosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArosAcosTable Test Case
 */
class ArosAcosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ArosAcosTable
     */
    public $ArosAcos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ArosAcos',
        'app.Aros',
        'app.Acos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ArosAcos') ? [] : ['className' => ArosAcosTable::class];
        $this->ArosAcos = TableRegistry::getTableLocator()->get('ArosAcos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArosAcos);

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
