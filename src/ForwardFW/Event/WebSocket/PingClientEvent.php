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

namespace ForwardFW\Event\WebSocket;

use ForwardFW\WebSocket\Client;

class PingClientEvent
    extends AbstractClientEvent
{
    private string $message = '';

    public function __construct(Client $client, string $message)
    {
        parent::__construct($client);
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
