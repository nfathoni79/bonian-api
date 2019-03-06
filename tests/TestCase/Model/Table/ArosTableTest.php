<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArosTable Test Case
 */
class ArosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ArosTable
     */
    public $Aros;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::getTableLocator()->exists('Aros') ? [] : ['className' => ArosTable::class];
        $this->Aros = TableRegistry::getTableLocator()->get('Aros', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Aros);

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
