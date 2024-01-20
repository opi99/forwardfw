<?php

declare(strict_types=1);

namespace ForwardFW\WebSocket;

class Client
{
    protected \Socket $socket;

    public function __construct(\Socket $socket)
    {
        $this->socket = $socket;
    }

    public function getSocket(): \Socket
    {
        return $this->socket;
    }

    public function getIp(): string
    {
        $clientIp = '';
        socket_getpeername($this->socket, $clientIp);
        return $clientIp;
    }

    public function getPort(): string
    {
        $clientIp = '';
        $clientPort = 0;
        socket_getpeername($this->socket, $clientIp, $clientPort);
        return $clientPort;
    }
}
