<?php
/**
 * Created by PhpStorm.
 * User: laurentnegre
 * Date: 11/04/2018
 * Time: 18:06
 */

namespace Dsw\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use WebSocket\Client;

/**
 * Class EchoCommand
 *
 * @package Dsw\Command
 */
class EchoCommand extends Command
{

    const PROCESS_DONE    = 0;
    const PROCESS_START   = 1;
    const PROCESS_RUNNING = 2;
    const PROCESS_FINISH  = 3;


    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * @var string
     */
    private $processId = null;

    /**
     * @var int
     */
    private $status = self::PROCESS_START;

    /**
     * AbstractWebSocketCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->webSocketClient = new Client("ws://127.0.0.1:8080");
    }

    protected function configure()
    {
        $this->setName('dsw:test-command')->setDescription('Display status Test Command')
             ->setHelp('This command allows you to test command')
             ->addArgument('pid', InputArgument::REQUIRED, 'The process id')
             ->addArgument('time', InputArgument::REQUIRED, 'The process time')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     * @throws \WebSocket\BadOpcodeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        $time = $input->getArgument('time');
        $this->processId = $input->getArgument('pid');

        $this->status = self::PROCESS_START;
        $this->sendMessage('Process #'.$input->getArgument('pid').' started for '.$time.'sc');
        sleep($input->getArgument('time'));
        $this->status = self::PROCESS_DONE;

        $timeEnd = microtime(true);
        $doneTime = $timeEnd - $timeStart;

        $this->sendMessage('Process #'.$input->getArgument('pid').' for '.$time.'sc done on '.number_format ($doneTime, 2).'sc');

        return 0;
    }

    /**
     * @param string $message
     *
     * @throws \WebSocket\BadOpcodeException
     */
    protected function sendMessage(string $message): void
    {

        $message = [
            'id'      => $this->processId,
            'status'  => $this->status,
            'message' => $message,
        ];
        $this->webSocketClient->send(json_encode($message));
    }
}