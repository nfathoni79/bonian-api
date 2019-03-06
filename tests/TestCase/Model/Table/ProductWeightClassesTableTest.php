<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductWeightClassesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductWeightClassesTable Test Case
 */
class ProductWeightClassesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductWeightClassesTable
     */
    public $ProductWeightClasses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductWeightClasses',
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
        $config = TableRegistry::getTableLocator()->exists('ProductWeightClasses') ? [] : ['className' => ProductWeightClassesTable::class];
        $this->ProductWeightClasses = TableRegistry::getTableLocator()->get('ProductWeightClasses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductWeightClasses);

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
}
