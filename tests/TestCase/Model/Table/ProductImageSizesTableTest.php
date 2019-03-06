<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductImageSizesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductImageSizesTable Test Case
 */
class ProductImageSizesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductImageSizesTable
     */
    public $ProductImageSizes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductImageSizes',
        'app.ProductImages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductImageSizes') ? [] : ['className' => ProductImageSizesTable::class];
        $this->ProductImageSizes = TableRegistry::getTableLocator()->get('ProductImageSizes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductImageSizes);

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
