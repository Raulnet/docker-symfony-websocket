<?php
/**
 * Created by PhpStorm.
 * User: laurentnegre
 * Date: 11/04/2018
 * Time: 18:17
 */

namespace Dsw\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use WebSocket\Client;


/**
 * Class ProcessCommand
 *
 * @package Dsw\Command
 */
class ProcessCommand extends Command
{
    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * ProcessCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->webSocketClient = new Client("ws://127.0.0.1:8080");
    }

    protected function configure()
    {
        $this->setName('dsw:run-process')->setDescription('run process')
             ->setHelp('This command allows you to run process')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     * @throws \WebSocket\ConnectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $numProcess = 10;
        // launch 10 process in same time
        for ($i = 0; $i < $numProcess; $i++) {
            $this->launchProcess($i);
        }
        while ($i < 50) { // while for MAX 50 chain process
            $message = $this->webSocketClient->receive();
            if ($message) {
                $data = json_decode($message);
                $output->writeln($data->message);
                // if process done launch an other
                if (property_exists($data, 'status') && $data->status === EchoCommand::PROCESS_DONE) {
                    $this->launchProcess($i++);
                }

            }
        }

        $output->writeln($i . ' process done');
        return 0;
    }

    /**
     * @param int $id
     */
    private function launchProcess(int $id): void
    {
        $time    = rand(3, 7);
        $process = new Process('./bin/console dsw:test-command ' . $id . ' ' . $time);
        $process->start();
    }
}