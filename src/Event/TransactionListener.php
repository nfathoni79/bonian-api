<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 06/05/2019
 * Time: 9:51
 */

namespace App\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\Event;


class TransactionListener implements EventListenerInterface
{

    public function implementedEvents()
    {
        return [
            'Controller.Ipn.success' => 'success',
            'Controller.Ipn.expired' => 'expired',
        ];
    }

    public function success(Event $event)
    {
        // Code to update transaction
        //debug($event->getSubject());
    }

    public function expired(Event $event)
    {
        // Code to update statistics
    }
}