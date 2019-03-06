<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductOptionValueListsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductOptionValueListsTable Test Case
 */
class ProductOptionValueListsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductOptionValueListsTable
     */
    public $ProductOptionValueLists;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductOptionValueLists',
        'app.ProductOptionPrices',
        'app.Options',
        'app.OptionValues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductOptionValueLists') ? [] : ['className' => ProductOptionValueListsTable::class];
        $this->ProductOptionValueLists = TableRegistry::getTableLocator()->get('ProductOptionValueLists', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductOptionValueLists);

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
