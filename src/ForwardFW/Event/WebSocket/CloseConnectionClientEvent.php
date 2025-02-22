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

class CloseConnectionClientEvent
    extends AbstractClientEvent
{
    private int $code = 0;

    private string $message = '';

    public function __construct(Client $client, int $code, string $message)
    {
        parent::__construct($client);
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
