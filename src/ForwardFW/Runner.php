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
    extends MiddlewareIterator
    implements RequestHandlerInterface
{
    private const MULTI_LINE_HEADERS = [
        'set-cookie',
    ];

    public function __construct(
        \ForwardFW\Config\Runner $config
    ) {
        parent::__construct($config);
    }

    public function run()
    {
        // Put ServiceManager into own Middleware
        $this->initializeServiceManager();
        $this->registerServices();
        $this->outputResponse(
            $this->runMiddlewares()
        );
        $this->stopServices();
    }

    protected function initializeServiceManager()
    {
        $serviceManagerConfig = $this->config->getServiceManager();
        $class = $serviceManagerConfig->getExecutionClassName();
        $this->serviceManager = new $class($serviceManagerConfig);
    }

    protected function registerServices()
    {
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->registerService($serviceConfig);
        }
    }

    protected function runMiddlewares(): ResponseInterface
    {
        $request = ServerRequestFactory::createFromGlobals();
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
                header($name . ': ' . (is_array($values) ? implode(', ', $values) : $values));
            }
        }

        $this->outputBody($response);
    }


    private function outputBody(ResponseInterface $response): void
    {
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        echo $body->__toString();die();
    }

    protected function stopServices()
    {
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->stopService($serviceConfig->getInterfaceName());
        }
    }
}
