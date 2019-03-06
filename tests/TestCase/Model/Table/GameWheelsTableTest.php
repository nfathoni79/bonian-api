<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameWheelsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameWheelsTable Test Case
 */
class GameWheelsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GameWheelsTable
     */
    public $GameWheels;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GameWheels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GameWheels') ? [] : ['className' => GameWheelsTable::class];
        $this->GameWheels = TableRegistry::getTableLocator()->get('GameWheels', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GameWheels);

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
