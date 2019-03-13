<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\MidTransComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\MidTransComponent Test Case
 */
class MidTransComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\MidTransComponent
     */
    public $MidTrans;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->MidTrans = new MidTransComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MidTrans);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
