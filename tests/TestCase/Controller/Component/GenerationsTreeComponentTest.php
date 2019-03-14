<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\GenerationsTreeComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\GenerationsTreeComponent Test Case
 */
class GenerationsTreeComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\GenerationsTreeComponent
     */
    public $GenerationsTree;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->GenerationsTree = new GenerationsTreeComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GenerationsTree);

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
