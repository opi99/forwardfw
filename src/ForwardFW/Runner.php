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

declare(strict_types=1);

namespace ForwardFW;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Runner
    implements RequestHandlerInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /** @var \ForwardFW\Config\Runner */
    protected $config;

    /** @var \ArrayIterator */
    protected $middlewareIterator;

    public function __construct(
        \ForwardFW\Config\Runner $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response
    ) {
        $this->config = $config;
        $this->middlewareIterator = $this->config->getMiddlewares()->getIterator();
        $this->request  = $request;
        $this->response = $response;
    }

    public function run()
    {
        $this->initializeServiceManager();
        $this->registerServices();
        $this->runMiddlewares();
        $this->stopServices();
    }

    protected function initializeServiceManager()
    {
        $serviceManagerConfig = $this->config->getServiceManager();
        $class = $serviceManagerConfig->getExecutionClassName();
        $this->serviceManager = new $class($serviceManagerConfig, $this->request, $this->response);
    }

    protected function registerServices()
    {
        $this->response->addLog('Register Services');

        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->registerService($serviceConfig);
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middlewareConfig = $this->middlewareIterator->next();
        $strFilterClass = $middlewareConfig->getExecutionClassName();
        $middleware = new $strFilterClass($middlewareConfig);
        $middleware->process($request, $this);
    }

    protected function runMiddlewares()
    {
        $this->handle($request);
    }

    protected function stopServices()
    {
        $this->response->addLog('Stop Services');
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->stopService($serviceConfig->getInterfaceName());
        }
    }
}
