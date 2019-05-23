<?php

namespace App\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Class DatabaseTransport
 * @package MemberPanel\Mailer\Transport
 * @property \App\Model\Table\EmailQueueTable $EmailQueue;
 */
class DatabaseTransport extends AbstractTransport
{
    protected $EmailQueue;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        //$this->EmailQueue = TableRegistry::get('EmailQueue');
        $this->EmailQueue = (new TableLocator())->get('EmailQueue');
    }

    public function send(Email $email)
    {
        // Do something.
        $header = $email->getHeaders(['sender', 'replyTo', 'cc', 'bcc']);
        foreach($email->getFrom() as $from_email => $from_name) {
            break;
        }

        foreach($email->getTo() as $to_email => $to_name) {
            break;
        }


        $entity = $this->EmailQueue->newEntity([
            'email' => $to_email,
            'subject' => $email->getSubject(),
            'from_name' => $from_name,
            'from_email' => $from_email,
            'template' => $email->getTemplate(),
            'format' => $email->getEmailFormat(),
            'theme' => $email->viewBuilder()->getTheme(),
            'headers' => serialize($header),
            'text' => $email->message(Email::MESSAGE_TEXT),
            'html' => $email->message(Email::MESSAGE_HTML)
        ]);


        $email_queue = $this->EmailQueue->save($entity);


        if ($listen = Configure::read('Mailer.listen')) {
            foreach($listen as $network) {
                list($host, $port) = explode(':', $network);

                try {
                    $socket = new \Cake\Network\Socket([
                        'host' => $host,
                        'port' => $port,
                        'timeout' => 10
                    ]);
                    if ($socket->connect()) {
                        $socket->write('processing-mailer');
                    }
                } catch(\Exception $e) {}

                break;
            }
        }








        return $email_queue;
    }
}