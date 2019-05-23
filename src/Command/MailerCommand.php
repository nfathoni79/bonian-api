<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Email;
use Cake\Core\Configure;

/**
 * Mailer command.
 * @property \MemberPanel\Model\Table\EmailQueueTable $EmailQueue
 */
class MailerCommand extends BaseCommand
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('EmailQueue');
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
            ->addOption('daemon', [
                'help' => 'Run as daemon with react PHP socket',
                'boolean' => true
            ]);

        return $parser;
    }




    protected function checkingData(ConsoleIo $io)
    {
        try {
            $this->EmailQueue->getConnection()->transactional(function (\Cake\Database\Connection $conn) use ($io) {

                $emails = $this->EmailQueue->find()
                    ->where([
                        'sent' => 0,
                        'send_tries <' => 3,
                        'locked' => 0
                    ])
                    ->limit(10)
                    ->orderAsc('created');
                $emails
                    ->extract('id')
                    ->through(function (\Cake\Collection\Iterator\ExtractIterator $ids) {
                        if (!$ids->isEmpty()) {
                            $this->EmailQueue->updateAll(['locked' => true], ['id IN' => $ids->toList()]);
                        }
                        return $ids;
                    });

                $conn->commit();

                /**
                 * @var \MemberPanel\Model\Entity\EmailQueue $mail
                 */
                foreach($emails->toList() as $mail) {
                    //process sending email using mailgun or other transport

                    $io->info('processing mail entry...');

                    $is_windows = PHP_OS === 'WINNT';
                    $php = $is_windows ? 'php' : '/usr/bin/php';

                    $exec = $is_windows ? 'start "" /B ' : '';
                    $exec .= "cd " . ROOT . " && ";
                    $exec .= "$php bin/cake.php mailer_process --process {$mail->get('id')}";
                    $exec .= $is_windows ? " | echo {$mail->get('id')}" : ' &';
                    $o = shell_exec($exec);
                    $io->info($o);

                }
            });


        } catch(\Exception $e) {
            $io->error($e->getMessage());
        }
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
        if ($args->getOption('daemon') && !$args->getOption('process')) {

            //first check older
            $this->checkingData($io);

            $loop = \React\EventLoop\Factory::create();

            foreach(Configure::read('Mailer.listen') as $listen) {
                $socket = new \React\Socket\Server($listen, $loop);

                $socket->on('connection', function (\React\Socket\ConnectionInterface $connection) use ($io) {
                    //$connection->write("Hello " . $connection->getRemoteAddress() . "!\n");
                    //$connection->write("Welcome to this amazing server!\n");
                    // $connection->write("Here's a tip: don't say anything.\n");

                    $connection->on('data', function ($data) use ($connection, $io) {
                        switch(trim($data)) {
                            case 'processing-mailer':
                                $this->checkingData($io);
                                break;
                        }
                        $connection->close();
                    });
                });
            }


            $loop->run();
        }


    }
}
