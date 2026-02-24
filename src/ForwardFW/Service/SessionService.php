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

namespace ForwardFW\Service;

use ForwardFW\Auth\AuthResult;
use ForwardFW\Auth\Event\LoginEvent;

/**
 * This service manages session data.
 */
class SessionService
    extends AbstractService
    implements SessionServiceInterface, StartAlways
{
    public function set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $_SESSION[$name] ?? $default;
    }

    public function loginEvent(LoginEvent $event): void
    {
        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('SessionService: Login Event');

        if ($event->getAuthResult()->isFreshLogin()) {
            session_regenerate_id(true);
        }

        $this->set(AuthResult::class, $event->getAuthResult());
    }

    public function start(): void
    {
        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('SessionService: Started');

        session_start([
            'name' => 'sid',
            'cookie_secure' => 1,
            'use_only_cookies' => 1,
            'cookie_httponly' => 1,
            'use_strict_mode' => 1,
            'cookie_samesite' => 'Strict',
        ]);

        $eventDispatcher = $this->serviceManager->getService(\Psr\EventDispatcher\EventDispatcherInterface::class);
        $eventDispatcher->addListener([$this, 'loginEvent'], LoginEvent::class);
    }

    public function stop(): void
    {
        session_commit();

        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('SessionService: End');
    }
}
