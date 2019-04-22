<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DigitalsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DigitalsTable Test Case
 */
class DigitalsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DigitalsTable
     */
    public $Digitals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Digitals',
        'app.DigitalDetails'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Digitals') ? [] : ['className' => DigitalsTable::class];
        $this->Digitals = TableRegistry::getTableLocator()->get('Digitals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Digitals);

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
