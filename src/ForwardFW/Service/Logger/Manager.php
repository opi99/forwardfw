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

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger which transfers all loggings to multiple Logger
 */
class Manager
    extends AbstractLogger
    implements LoggerInterface
{
    protected array $loggers = [];

    public function __construct(\ForwardFW\Config\Service\Logger\Manager $config, \ForwardFW\ServiceManager $manager)
    {
        foreach ($config->getSubServicesConfig() as $loggerConfig) {
            $this->loggers[] = $manager->getService($loggerConfig->getExecutionClassName());
        }

    }

    public function log($level, $message, $context = []): void
    {
        foreach ($this->loggers as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
