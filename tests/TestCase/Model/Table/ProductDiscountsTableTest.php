<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductDiscountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductDiscountsTable Test Case
 */
class ProductDiscountsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductDiscountsTable
     */
    public $ProductDiscounts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductDiscounts',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductDiscounts') ? [] : ['className' => ProductDiscountsTable::class];
        $this->ProductDiscounts = TableRegistry::getTableLocator()->get('ProductDiscounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductDiscounts);

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
