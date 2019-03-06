<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OptionTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OptionTypesTable Test Case
 */
class OptionTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OptionTypesTable
     */
    public $OptionTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OptionTypes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OptionTypes') ? [] : ['className' => OptionTypesTable::class];
        $this->OptionTypes = TableRegistry::getTableLocator()->get('OptionTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OptionTypes);

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
