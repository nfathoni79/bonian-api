<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubdistrictsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubdistrictsTable Test Case
 */
class SubdistrictsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SubdistrictsTable
     */
    public $Subdistricts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Subdistricts',
        'app.Cities',
        'app.Branches',
        'app.CustomerAddreses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Subdistricts') ? [] : ['className' => SubdistrictsTable::class];
        $this->Subdistricts = TableRegistry::getTableLocator()->get('Subdistricts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Subdistricts);

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
