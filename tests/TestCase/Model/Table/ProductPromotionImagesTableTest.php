<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductPromotionImagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductPromotionImagesTable Test Case
 */
class ProductPromotionImagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductPromotionImagesTable
     */
    public $ProductPromotionImages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductPromotionImages',
        'app.ProductPromotions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductPromotionImages') ? [] : ['className' => ProductPromotionImagesTable::class];
        $this->ProductPromotionImages = TableRegistry::getTableLocator()->get('ProductPromotionImages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductPromotionImages);

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
