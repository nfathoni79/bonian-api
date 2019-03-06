<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMetaTagsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMetaTagsTable Test Case
 */
class ProductMetaTagsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMetaTagsTable
     */
    public $ProductMetaTags;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductMetaTags',
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
        $config = TableRegistry::getTableLocator()->exists('ProductMetaTags') ? [] : ['className' => ProductMetaTagsTable::class];
        $this->ProductMetaTags = TableRegistry::getTableLocator()->get('ProductMetaTags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductMetaTags);

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
