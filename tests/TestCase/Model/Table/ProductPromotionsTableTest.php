<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductPromotionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductPromotionsTable Test Case
 */
class ProductPromotionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductPromotionsTable
     */
    public $ProductPromotions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductPromotions',
        'app.Products',
        'app.Orders',
        'app.ProductPromotionImages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductPromotions') ? [] : ['className' => ProductPromotionsTable::class];
        $this->ProductPromotions = TableRegistry::getTableLocator()->get('ProductPromotions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductPromotions);

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
