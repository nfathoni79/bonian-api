<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\V1;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Event\TransactionListener;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 * @property \App\Controller\Component\MidTransComponent $MidTrans
 * @property \App\Controller\Component\SendAuthComponent $SendAuth
 * @property \App\Controller\Component\NotificationComponent $Notification
 * @property \App\Controller\Component\PusherComponent $Pusher
 * @property \App\Controller\Component\MailerComponent $Mailer
 * @property \App\Controller\Component\ToolsComponent $Tools
 * @property \App\Controller\Component\ChatKitComponent $ChatKit
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     * @throws \Exception
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        $this->loadComponent('SendAuth');
        $this->loadComponent('MidTrans');
        $this->loadComponent('Mailer', ['transport' => 'default']);
        $this->loadComponent('Notification');
        $this->loadComponent('Pusher');
        $this->loadComponent('Tools');
        $this->loadComponent('ChatKit');
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    /**
     * beforeRender callback
     *
     * @param Event $event An Event instance
     * @return null
     */
    public function beforeRender(Event $event)
    {

        $this->viewBuilder()->setClassName('App.Json');
        return null;

    }

    public function beforeFilter(Event $event)
    {
        $this->getEventManager()->on(new TransactionListener());
        return parent::beforeFilter($event);
    }
}
