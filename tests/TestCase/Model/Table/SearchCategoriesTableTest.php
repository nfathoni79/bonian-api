<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SearchCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SearchCategoriesTable Test Case
 */
class SearchCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SearchCategoriesTable
     */
    public $SearchCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SearchCategories',
        'app.SearchTerms',
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
        $config = TableRegistry::getTableLocator()->exists('SearchCategories') ? [] : ['className' => SearchCategoriesTable::class];
        $this->SearchCategories = TableRegistry::getTableLocator()->get('SearchCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SearchCategories);

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
