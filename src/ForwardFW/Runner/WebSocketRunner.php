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

use ForwardFW\Event\WebSocket\NewClientEvent;
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

    protected function preRun(): void
    {
        parent::preRun();
        $this->registerSignals();
        $this->initServerSocket();
    }

    public function run(): void
    {
        $this->preRun();
        $this->mainLoop();
        $this->postRun();
    }

    protected function postRun(): void
    {
        $this->closeAllSockets();
        parent::postRun();
    }


    protected function mainLoop(): void
    {
        while (!$this->shutDown) {
            $this->checkNewConnections();
            $this->readAndExecute();

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
            case SIGCHLD:
                // Ignored, that a child changed state
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

    const MASK_126 = 126;
    const MASK_127 = 127;
    const MASK_FRAME = 0b1111;
    const MASK_RSV = 0b1110000;
    const MASK_PAYLOAD = 0b1111111;

    const IS_LAST_FRAME = 0x80;

    const FRAME_CONTINUATION = 0x0;
    const FRAME_TEXT = 0x1;
    const FRAME_BIN = 0x2;
    const FRAME_CLOSE = 0x8;
    const FRAME_PING = 0x9;
    const FRAME_PONG = 0xA;

    const IS_MASKED = 0x80;

    protected function unseal(string $socketData)
    {
        $isClose = false;

        $firstByte = ord($socketData[0]);
        $secondByte = ord($socketData[1]);

        $lastFrame = (bool) ($firstByte & self::IS_LAST_FRAME);

        if (!$lastFrame) {
            /** @TODO This is not last frame, we should read till last frame, before we give the data back. */
            var_dump('NOT YET SUPPORTED! Data send over multiple frames, yet last frame would produce garbage.');
            return '';
        }

        $opcode = $firstByte & self::MASK_FRAME;
        $rsv = $firstByte & self::MASK_RSV;

        switch ($opcode) {
            case self::FRAME_CONTINUATION:
                var_dump('NOT YET SUPPORTED! Continuation frame?');
                return;
                break;
            case self::FRAME_TEXT:
                var_dump('text');
                break;
            case self::FRAME_BIN:
                /** @TODO Binary data. */
                var_dump('NOT YET SUPPORTED! Do we need binary?');
                return '';
                break;
            case self::FRAME_CLOSE:
                var_dump('close');
                $isClose = true;
                break;
            case self::FRAME_PING:
                var_dump('NOT YET SUPPORTED! We should send a pong here?');
                return '';
                break;
            case self::FRAME_PONG:
                var_dump('NOT YET SUPPORTED! We didn\'t send a ping yet.');
                return '';
                break;
            default:
                var_dump('NOT YET SUPPORTED! Frametype ' . $opcode . ' not defined in RFC6455?');
        }

        $masked = (bool) ($secondByte & self::IS_MASKED);
        $length = $secondByte & self::MASK_PAYLOAD;

        $dataStart = 2;
        if ($length === self::MASK_126) {
            $dataStart = 6;
        } elseif ($length === self::MASK_127) {
            $dataStart = 10;
        }

        $totalLength = $dataStart + $length;
        $mask = '';
        if ($masked) {
            $totalLength += 4;
            $mask = substr($socketData, $dataStart, 4);
            $dataStart += 4;
        }

        if ($totalLength > strlen($socketData)) {
            /** @TODO The frame is longer then we read from socket => Need buffer */
            var_dump('NOT YET SUPPORTED! Not all data for WebSocket frame read from socket. So we will read garbage next.');
            return '';
        }

        $content = substr($socketData, $dataStart, $length);

        if ($masked) {
            $unmaskedContent = '';
            for ($i = 0; $i < $length; ++$i) {
                $unmaskedContent .= $content[$i] ^ $mask[$i % 4];
            }
            $content = $unmaskedContent;
        }

        if ($totalLength < strlen($socketData)) {
            /** @TODO We read more from socket as this frame is long => Continue handling! */
            var_dump('NOT YET SUPPORTED! We read more data from WebSocket as this frame has, should be handled as next.');
        }

        if ($isClose) {
            $closeCode = unpack('n', substr($content, 0, 2))[1];

            var_dump('Connection closed with code: "' . $closeCode . '". See https://datatracker.ietf.org/doc/html/rfc6455#section-7.4');
            $content = substr($content, 2);

            /** @TODO Need to implement Close frame response https://datatracker.ietf.org/doc/html/rfc6455#section-5.5.1 */

            return '';
        }

        // Do not return, create events with the data out of frames.

        return $content;
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
            $this->sendNewConnectionEvent($client);
        }

    }

    protected function sendNewConnectionEvent(Client $client): void
    {
        $newClientEvent = new NewClientEvent($client);

        /** @var \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->serviceManager->getService(\Psr\EventDispatcher\EventDispatcherInterface::class);
        $eventDispatcher->dispatch($newClientEvent);
    }

    protected function readAndExecute()
    {
        if (!count($this->clients)) {
            return;
        }

        $socketsToCheck = [];

        foreach ($this->clients as $client) {
            $socketsToCheck[] = $client->getSocket();
        }

        $unused = [];
        socket_select($socketsToCheck, $unused, $unused, 0, 10);

        if (count($socketsToCheck)) {
            foreach ($socketsToCheck as $socketToRead) {
                while (socket_recv($socketToRead, $socketData, 4096, MSG_DONTWAIT) >= 1) {
                    /** @TODO read longer message blocks */
                    $socketMessage = $this->unseal($socketData);
                    $message = json_decode($socketMessage, true);
                    // $this->processMessage($message);
                }
            }
        }
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
