<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Email;

/**
 * MailerProcess command.
 */
class MailerProcessCommand extends Command
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('EmailQueue');
    }


    protected function processData($id, ConsoleIo $io)
    {
        $success = [];

        $mail = $this->EmailQueue->find()
            ->where([
                'id' => $id,
                //'locked' => 1,
                'send_tries <' => 3,
            ])
            ->first();
        if ($mail) {
            try {
                $email = new Email('mailgun');
                $email->setFrom([$mail->get('from_email') => $mail->get('from_name')])
                    ->setTo($mail->get('email'))
                    ->setHeaders(unserialize($mail->get('headers')))
                    //->setLayout($mail->get('layout'))
                    //->setTemplate($mail->get('template'))
                    ->setEmailFormat($mail->get('format'))
                    ->setSubject($mail->get('subject'));



                switch($mail->get('format')) {
                    case 'html':
                        $success[] = $email->send($mail->get('html'));
                        break;
                    case 'text':
                        $success[] = $email->send($mail->get('text'));
                        break;
                    case 'both':
                        //$success[] = $email->send($mail->get('text'));
                        //$success[] = $email->send($mail->get('html'));
                        break;
                }
            } catch(\Exception $e) {
                $io->error($e->getMessage());
                $mail->set('error', $e->getMessage());
                $mail->set('locked', 0);
                $mail->set('send_tries', $mail->get('send_tries') + 1);
            }



            if(count($success) > 0) {
                $this->EmailQueue->delete($mail);
            } else {
                $this->EmailQueue->save($mail);
            }
        }

    }


    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
        $parser
            ->addOption('process', [
                'help' => 'Run as child process'
            ]);
        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        //run as child process
        if ($email_id = $args->getOption('process')) {
            $this->processData($email_id, $io);
            $io->info($email_id);
        }
    }
}
