<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\NotificationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\NotificationComponent Test Case
 */
class NotificationComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\NotificationComponent
     */
    public $Notification;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Notification = new NotificationComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Notification);

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
