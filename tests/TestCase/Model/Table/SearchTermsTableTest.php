<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SearchTermsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SearchTermsTable Test Case
 */
class SearchTermsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SearchTermsTable
     */
    public $SearchTerms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SearchTerms',
        'app.SearchStats'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SearchTerms') ? [] : ['className' => SearchTermsTable::class];
        $this->SearchTerms = TableRegistry::getTableLocator()->get('SearchTerms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SearchTerms);

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
