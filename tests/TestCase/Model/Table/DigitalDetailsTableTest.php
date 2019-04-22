<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DigitalDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DigitalDetailsTable Test Case
 */
class DigitalDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DigitalDetailsTable
     */
    public $DigitalDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DigitalDetails',
        'app.Digitals'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DigitalDetails') ? [] : ['className' => DigitalDetailsTable::class];
        $this->DigitalDetails = TableRegistry::getTableLocator()->get('DigitalDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DigitalDetails);

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
