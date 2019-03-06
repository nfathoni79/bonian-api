<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PriceSettingDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PriceSettingDetailsTable Test Case
 */
class PriceSettingDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PriceSettingDetailsTable
     */
    public $PriceSettingDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PriceSettingDetails',
        'app.PriceSettings',
        'app.Products',
        'app.ProductOptionPrices'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PriceSettingDetails') ? [] : ['className' => PriceSettingDetailsTable::class];
        $this->PriceSettingDetails = TableRegistry::getTableLocator()->get('PriceSettingDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PriceSettingDetails);

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
