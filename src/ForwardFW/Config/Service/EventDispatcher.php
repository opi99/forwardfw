<?php

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

namespace ForwardFW\Config\Service;

/**
 * Config for a Event Dispatching Service.
 */
class EventDispatcher extends \ForwardFW\Config\Service
{
    protected string $executionClassName = \ForwardFW\Service\EventDispatcher::class;
    protected string $interfaceName = \Psr\EventDispatcher\EventDispatcherInterface::class;

    protected array $listeners = [];

    public function addListener(callable $listener, string $event)
    {
        if (!isset($this->listener[$event])) {
            $this->listeners[$event] = [];
        }
        $this->listeners[$event][] = $listener;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}
