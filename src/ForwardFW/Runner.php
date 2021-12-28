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

use ForwardFW\Factory\ResponseFactory;
use ForwardFW\Factory\ServerRequestFactory;
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
        \ForwardFW\Config\Runner $config
    ) {
        $this->config = $config;
        $this->middlewareIterator = $this->config->getMiddlewares()->getIterator();
    }

    public function run()
    {
        // Put ServiceManager into own Middleware
//         $this->initializeServiceManager();
//         $this->registerServices();
        $this->outputResponse(
            $this->runMiddlewares()
        );
//         $this->stopServices();
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
        $middlewareConfig = $this->middlewareIterator->current();
        $this->middlewareIterator->next();

        if ($middlewareConfig !== null) {
            $strFilterClass = $middlewareConfig->getExecutionClassName();
            $middleware = new $strFilterClass($middlewareConfig);
            return $middleware->process($request, $this);
        } else {
            // No Middleware which runs?
            $factory = new ResponseFactory();
            return $factory->createResponse();
        }
    }

    protected function runMiddlewares(): ResponseInterface
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest('', '', []);
        return $this->handle($request);
    }

    protected function outputResponse(ResponseInterface $response)
    {
        header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());

        foreach ($response->getHeaders() as $name => $values) {
            if (in_array(strtolower($name), self::MULTI_LINE_HEADERS, true)) {
                foreach ($values as $value) {
                    header($name . ': ' . $value, false);
                }
            } else {
                header($name . ': ' . implode(', ', $values));
            }
        }

        $response->getBody()->__toString();
    }

    protected function stopServices()
    {
        $this->response->addLog('Stop Services');
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->stopService($serviceConfig->getInterfaceName());
        }
    }
}
