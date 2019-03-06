<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductWarrantiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductWarrantiesTable Test Case
 */
class ProductWarrantiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductWarrantiesTable
     */
    public $ProductWarranties;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProductWarranties',
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
        $config = TableRegistry::getTableLocator()->exists('ProductWarranties') ? [] : ['className' => ProductWarrantiesTable::class];
        $this->ProductWarranties = TableRegistry::getTableLocator()->get('ProductWarranties', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductWarranties);

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
