<?php
/**
 * Created by PhpStorm.
 * User: laurentnegre
 * Date: 12/04/2018
 * Time: 16:08
 */

namespace Dsw\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Dsw\Command\Model\WebSocketMessage;

/**
 * Class ServerWebSocketCommand
 *
 * @package Dsw\Command
 */
class ServerWebSocketCommand extends command
{
    protected function configure()
    {
        $this->setName('dsw:web-socket')->setDescription('run server websocket')
             ->setHelp('This command allows you to run werver web socket')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $httpServer = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketMessage()
                )
            ),
            8080
        );
        $output->writeln('Server Websocket running');
        $httpServer->run();

    }
}