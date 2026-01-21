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

namespace ForwardFW;

use ForwardFW\Factory\ServerRequestFactory;
use ForwardFW\Middleware\MiddlewareIterator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Runner
{
    /** @var \ForwardFW\Config\Runner */
    protected $config;

    /** @var \ForwardFW\ServiceManager */
    protected $serviceManager;

    public function __construct(
        \ForwardFW\Config\Runner $config
    ) {
        $this->config = $config;
    }

    protected function preRun(): void
    {
        // Put ServiceManager into own Middleware
        $this->initializeServiceManager();
        $this->registerContainerVars();
        $this->registerServices();
    }

    protected function postRun(): void
    {
        $this->stopServices();
    }

    public function run(): void
    {
        $this->preRun();
        $this->postRun();
    }

    protected function initializeServiceManager(): void
    {
        $serviceManagerConfig = $this->config->getServiceManager();
        $class = $serviceManagerConfig->getExecutionClassName();
        $this->serviceManager = new $class($serviceManagerConfig);
    }

    protected function registerContainerVars(): void
    {
        $this->serviceManager->registerContainerVars($this->config->getContainerVars());
    }

    protected function registerServices(): void
    {
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->registerService($serviceConfig);
        }
    }

    protected function stopServices(): void
    {
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->stopService($serviceConfig);
        }
    }
}
