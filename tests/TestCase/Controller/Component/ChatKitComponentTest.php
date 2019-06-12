<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ChatKitComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\ChatKitComponent Test Case
 */
class ChatKitComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\ChatKitComponent
     */
    public $ChatKit;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->ChatKit = new ChatKitComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChatKit);

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
