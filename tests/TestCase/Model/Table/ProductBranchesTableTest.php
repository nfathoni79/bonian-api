<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductBranchesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductBranchesTable Test Case
 */
class ProductBranchesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductBranchesTable
     */
    public $ProductBranches;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductBranches',
        'app.Products',
        'app.Branches'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductBranches') ? [] : ['className' => ProductBranchesTable::class];
        $this->ProductBranches = TableRegistry::getTableLocator()->get('ProductBranches', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductBranches);

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
