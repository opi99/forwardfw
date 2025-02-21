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

namespace ForwardFW\Service\Logger;

use Clockwork\Request\Log;
use Clockwork\Support\Vanilla\Clockwork;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger for Clockwork
 */
class ClockworkLogger
    extends AbstractLogger
    implements LoggerInterface
{
    protected Log $clockworkLog;

    protected $clockwork;

    public function __construct(
        \ForwardFW\Config $config,
        // We have no DI yet
        \ForwardFW\ServiceManager $serviceManager
    ) {
        // $this->clockworkLog = new Log();
        $this->clockwork = new Clockwork($config->getAsClockworkConfig());
    }

    public function getClockwork(): Clockwork
    {
        return $this->clockwork;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string  $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, $message, array $context = [])
    {
        $this->clockwork->log($level, $message, $context);
    }
}
