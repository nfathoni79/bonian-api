<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductDiscussionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductDiscussionsTable Test Case
 */
class ProductDiscussionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductDiscussionsTable
     */
    public $ProductDiscussions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductDiscussions',
        'app.Products',
        'app.Customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductDiscussions') ? [] : ['className' => ProductDiscussionsTable::class];
        $this->ProductDiscussions = TableRegistry::getTableLocator()->get('ProductDiscussions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductDiscussions);

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
