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

namespace ForwardFW\Config\Runner;

/**
 * Config for the Runner.
 */
class WebSocketRunner extends \ForwardFW\Config\Runner
{
    /** @var string Class Name of executor */
    protected string $executionClassName = \ForwardFW\Runner\WebSocketRunner::class;

    protected int $port;

    protected string $host;

    public function setPort(int $port): self
    {
        if ($port <= 0 || $port > 65535)
        {
            throw new \Exception('Port number out of range');
        }
        $this->port = $port;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
