<?php

declare(strict_types=1);

/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW\Runner;

use ForwardFW\Runner;
use ForwardFW\WebSocket\Client;

class WebSocketRunner
    extends Runner
{
    protected \Socket $serverSocket;

    /** @var array<Client> */
    protected array $clients = [];

    public function __construct(
        \ForwardFW\Config\Runner\WebSocketRunner $config
    ) {
        parent::__construct($config);
    }

    protected function preRun()
    {
        parent::preRun();
        $this->registerSignals();
        $this->initServerSocket();
    }

    public function run()
    {
        $this->preRun();
        $this->mainLoop();
        $this->postRun();
    }

    protected function postRun()
    {
        $this->closeAllSockets();
        parent::postRun();
    }


    protected function mainLoop()
    {
        while (!$this->shutDown) {
            $this->checkNewConnections();
            // $this->readAndExecute();

            /** @TODO Only on changes */
            // $this->sendBaseData();

            usleep(5000);
        }
    }


    protected function registerSignals(): void
    {
        $signals = [
            // Handled
            SIGTERM, SIGINT, SIGUSR1,

            // Ignored
            // Some of these are duplicated/aliased, listed here for completeness
            SIGHUP, SIGCHLD, SIGUSR2, SIGCONT, SIGQUIT, SIGILL, SIGTRAP, SIGABRT, SIGIOT, SIGBUS, SIGFPE, SIGSEGV, SIGPIPE, SIGALRM,
            SIGCONT, SIGTSTP, SIGTTIN, SIGTTOU, SIGURG, SIGXCPU, SIGXFSZ, SIGVTALRM, SIGPROF,
            SIGWINCH, SIGIO, SIGSYS, SIGBABY, SIGPOLL, SIGPWR, SIGSTKFLT
        ];

        pcntl_async_signals(true);
        foreach ($signals as $signal) {
            pcntl_signal($signal, array($this, 'handleSignal'));
        }
    }

    public function handleSignal($signo): void
    {
        switch ($signo) {
            case SIGINT:
            case SIGTERM:
                // Aufgaben zum Beenden bearbeiten
                $this->shutDown = true;
                break;
            case SIGHUP:
                // Aufgaben zum Neustart bearbeiten
                break;
            default:
                echo 'SIGNAL' . $signo;
                // Do nothing on other signals
        }
    }

    protected function initServerSocket(): void
    {
        $this->serverSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->serverSocket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->serverSocket, $this->config->getHost(), $this->config->getPort());
        socket_listen($this->serverSocket);
    }

    protected function closeAllSockets(): void
    {
        socket_close($this->serverSocket);
        foreach($this->clients as $client)
        {
            $this->sendDataToSocket(
                'message',
                [
                    'message' => 'Unknown reason',
                ],
                $client->getSocket()
            );
            @socket_close($client->getSocket());
        }
    }

    protected function sendDataToAllClients(string $type, array $data): void
    {
        foreach($this->clients as $client)
        {
            $this->sendDataToSocket($type, $data, $client->getSocket());
        }
    }

    protected function sendDataToSocket(string $type, array $data, \Socket $clientSocket): bool
    {
        $jsonMessage = json_encode([
            'type' => $type,
            'data' => $data,
        ]);

        $message = $this->seal($jsonMessage);
        $messageLength = strlen($message);

        $send = @socket_write($clientSocket, $message, $messageLength);

        if ($send === false) {
            //connection closed
            return false;
        }

        return true;
    }


    protected function unseal($socketData) {
        $length = ord($socketData[1]) & 127;
        if($length == 126) {
            $masks = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        }
        elseif($length == 127) {
            $masks = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        }
        else {
            $masks = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }
        $socketData = "";
        for ($i = 0; $i < strlen($data); ++$i) {
            $socketData .= $data[$i] ^ $masks[$i%4];
        }
        return $socketData;
    }

    protected function seal($message)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($message);

        if($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);
        return $header . $message;
    }

    protected function checkNewConnections()
    {
        // Check for new connects
        $socketsToCheck = [
            $this->serverSocket
        ];
        $unused = [];
        socket_select($socketsToCheck, $unused, $unused, 0, 10);

        if (count($socketsToCheck)) {
            $newSocket = socket_accept($this->serverSocket);
            $header = socket_read($newSocket, 1024);
            $this->doHandshake($header, $newSocket);

            $client = new Client($newSocket);
            $this->clients[] = $client;
            echo 'New connection from ' . $client->getIp() . "\n";
            $this->sendNewConnectionEvent($client);
            // $this->sendBaseData(true);
        }

    }

    protected function sendNewConnectionEvent(Client $client): void
    {

    }

    protected function doHandshake($headers, $clientSocket)
    {
        $headerLines = [];
        $lines = preg_split("/\r\n/", $headers);
        foreach($lines as $line)
        {
            $line = chop($line);
            if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
            {
                $headerLines[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headerLines['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $buffer = 'HTTP/1.1 101 Web Socket Protocol Handshake' . "\r\n"
            . 'Upgrade: websocket' . "\r\n"
            . 'Connection: Upgrade' . "\r\n"
            . 'WebSocket-Origin: ' . $this->config->getHost() . "\r\n"
            . 'WebSocket-Location: ws://' . $this->config->getHost() . ':' . $this->config->getPort() . "\r\n"
            . 'Sec-WebSocket-Accept:' . $secAccept . "\r\n\r\n";
        socket_write($clientSocket, $buffer, strlen($buffer));
    }
}
