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
            $this->closeClientConnection($client, 'UNKNOWN REASON', 1001);
        }
    }

    protected function closeClientConnection(Client $client, string $message, int $code)
    {
        $this->sendCloseFrame($message, $code, $client->getSocket());
        // Do not wait for CLOSE frame echo, FF and Chromium do not do this.
        @socket_close($client->getSocket());

        foreach ($this->clients as $key => $clientFound) {
            if ($clientFound === $client) {
                unset($this->clients[$key]);
                return;
            }
        }
    }

    protected function sendCloseFrame(string $message, int $code, \Socket $clientSocket)
    {
        $content = $this->seal(self::FRAME_CLOSE, $message, $code);
        $contentLength = strlen($content);
        $send = @socket_write($clientSocket, $content, $contentLength);
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

        $message = $this->seal(self::FRAME_TEXT, $jsonMessage);
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

    protected function unseal(string $socketData, Client $client)
    {
        $isClose = false;

        $firstByte = ord($socketData[0]);
        $secondByte = ord($socketData[1]);

        $lastFrame = (bool) ($firstByte & self::IS_LAST_FRAME);

        if (!$lastFrame) {
            /** @TODO This is not last frame, we should read till last frame, before we give the data back. */
            var_dump('NOT YET SUPPORTED! Data send over multiple frames, yet last frame would produce garbage.');
            $this->closeClientConnection($client, 'Multiple frames not supported', 1003);
            return '';
        }

        $opcode = $firstByte & self::MASK_FRAME;
        $rsv = $firstByte & self::MASK_RSV;

        switch ($opcode) {
            case self::FRAME_CONTINUATION:
                $this->closeClientConnection($client, 'Frame continuation not supported', 1003);
                return;
                break;
            case self::FRAME_TEXT:
                break;
            case self::FRAME_BIN:
                /** @TODO Binary data. */
                $this->closeClientConnection($client, 'Frame binary not supported', 1003);
                return '';
                break;
            case self::FRAME_CLOSE:
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
                $this->closeClientConnection($client, 'Your frame opcode is not defined in RFC6455', 1003);
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
            $this->closeClientConnection($client, 'Only max 4096 bytes supported yet', 1009);
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
            $content = substr($content, 2);

            echo 'Connection closed with code: "' . $closeCode . '" and message "' . $content . '"' . "\n";

            /** Close frame response https://datatracker.ietf.org/doc/html/rfc6455#section-5.5.1 */
            $this->closeClientConnection($client, $content, $closeCode);

            return '';
        }

        // Do not return, create events with the data out of frames.

        return $content;
    }

    protected function seal(int $type, string $message, int $code = 0)
    {
        if ($type === self::FRAME_CLOSE)
        {
            $message = pack('n', $code) . $message;
        }

        $length = strlen($message);

        /** @TODO Support masking? */
        /** @TODO Support fragmentation? */

        $firstByte = self::IS_LAST_FRAME | $type;
        $secondByte = /** self::IS_MASKED | */ 0;
        $header = '';

        if ($length <= 125) {
            $secondByte |= $length;
            $header = pack('CC', $firstByte, $secondByte);
        } elseif($length > 125 && $length < 65536) {
            $secondByte |= self::MASK_126;
            $header = pack('CCn', $firstByte, $secondByte, $length);
        } else {
            $secondByte |= self::MASK_127;
            $header = pack('CCJ', $firstByte, $secondByte, $length);
        }

        return $header . $message;
    }

    protected function checkNewConnections()
    {
        // Check for new connects
        $socketsToCheck = [
            $this->serverSocket
        ];
        $unused = null;
        socket_select($socketsToCheck, $unused, $unused, 0, 10);

        if (count($socketsToCheck)) {
            $newSocket = socket_accept($this->serverSocket);
            $header = socket_read($newSocket, 1024);

            if ($this->doHandshake($header, $newSocket)) {
                $client = new Client($newSocket);
                $this->clients[] = $client;
                $this->sendNewConnectionEvent($client);
            } else {
                socket_close($newSocket);
            }
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
        $unused = null;
        socket_select($socketsToCheck, $unused, $unused, 0, 10);
        if (count($socketsToCheck)) {
            foreach ($socketsToCheck as $socketToRead) {
                if (socket_recv($socketToRead, $socketData, 4096, MSG_DONTWAIT) >= 1) {
                    /** @TODO read longer message blocks */

                    $client = $this->findClientFromSocket($socketToRead);

                    $socketMessage = $this->unseal($socketData, $client);
                    $message = json_decode($socketMessage, true);
                    // $this->processMessage($message);
                }
            }
        }
    }

    protected function findClientFromSocket(\Socket $socket): Client
    {
        foreach ($this->clients as $client) {
            if ($client->getSocket() === $socket) {
                return $client;
            }
        }

        throw new \Exception('We have no client with this socket');
    }

    protected function doHandshake($headers, $clientSocket): bool
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

        if (count($headerLines) === 0) {
            return false;
        }
        if (($headerLines['Upgrade'] ?? null) !== 'websocket') {
            $buffer = 'HTTP/1.1 412 You need to request Upgrade to websocket' . "\r\n\r\n";
            socket_write($clientSocket, $buffer, strlen($buffer));
            return false;
        }
        if (($headerLines['Sec-WebSocket-Version'] ?? null) !== '13') {
            $buffer = 'HTTP/1.1 412 Only Sec-WebSocket-Version 13 supported' . "\r\n\r\n";
            socket_write($clientSocket, $buffer, strlen($buffer));
            return false;
        }

        if (!isset($headerLines['Sec-WebSocket-Key'])) {
            $buffer = 'HTTP/1.1 412 No Sec-WebSocket-Key given' . "\r\n\r\n";
            socket_write($clientSocket, $buffer, strlen($buffer));
            return false;
        }


        $secKey = $headerLines['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $buffer = 'HTTP/1.1 101 Web Socket Protocol Handshake' . "\r\n"
            . 'Upgrade: websocket' . "\r\n"
            . 'Sec-WebSocket-Version: 13' . "\r\n"
            . 'Connection: Upgrade' . "\r\n"
            . 'WebSocket-Origin: ' . $this->config->getHost() . "\r\n"
            . 'WebSocket-Location: ws://' . $this->config->getHost() . ':' . $this->config->getPort() . "\r\n"
            . 'Sec-WebSocket-Accept:' . $secAccept . "\r\n\r\n";
        socket_write($clientSocket, $buffer, strlen($buffer));

        return true;
    }
}
