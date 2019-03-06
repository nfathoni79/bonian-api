<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PriceSettingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PriceSettingsTable Test Case
 */
class PriceSettingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PriceSettingsTable
     */
    public $PriceSettings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PriceSettings',
        'app.Users',
        'app.PriceSettingDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PriceSettings') ? [] : ['className' => PriceSettingsTable::class];
        $this->PriceSettings = TableRegistry::getTableLocator()->get('PriceSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PriceSettings);

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
