<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductToCourriersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductToCourriersTable Test Case
 */
class ProductToCourriersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductToCourriersTable
     */
    public $ProductToCourriers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductToCourriers',
        'app.Products',
        'app.Courriers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProductToCourriers') ? [] : ['className' => ProductToCourriersTable::class];
        $this->ProductToCourriers = TableRegistry::getTableLocator()->get('ProductToCourriers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductToCourriers);

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
