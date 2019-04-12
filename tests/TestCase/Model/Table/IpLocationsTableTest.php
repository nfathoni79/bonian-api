<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IpLocationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IpLocationsTable Test Case
 */
class IpLocationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\IpLocationsTable
     */
    public $IpLocations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.IpLocations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('IpLocations') ? [] : ['className' => IpLocationsTable::class];
        $this->IpLocations = TableRegistry::getTableLocator()->get('IpLocations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->IpLocations);

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
