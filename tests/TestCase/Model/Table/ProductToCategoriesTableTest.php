<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductToCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductToCategoriesTable Test Case
 */
class ProductToCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductToCategoriesTable
     */
    public $ProductToCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductToCategories',
        'app.Products',
        'app.ProductCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductToCategories') ? [] : ['className' => ProductToCategoriesTable::class];
        $this->ProductToCategories = TableRegistry::getTableLocator()->get('ProductToCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductToCategories);

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
