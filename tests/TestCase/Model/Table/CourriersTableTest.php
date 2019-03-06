<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourriersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourriersTable Test Case
 */
class CourriersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CourriersTable
     */
    public $Courriers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Courriers',
        'app.OrderDetails',
        'app.ProductToCourriers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Courriers') ? [] : ['className' => CourriersTable::class];
        $this->Courriers = TableRegistry::getTableLocator()->get('Courriers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Courriers);

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
