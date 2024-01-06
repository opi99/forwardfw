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

namespace ForwardFW\Middleware;

use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareIterator
    implements RequestHandlerInterface
{
    use MiddlewareIteratorTrait;

    /** @var \ForwardFW\Config */
    protected $config;

    /** @var \ForwardFW\ServiceManager */
    protected $serviceManager;

    public function __construct(
        \ForwardFW\Config\Middleware\MiddlewareIteratorInterface $config
    ) {
        $this->config = $config;
        $this->middlewareIterator = $this->config->getMiddlewares()->getIterator();
    }

    public function setServiceManager(\ForwardFW\ServiceManager $serviceManager): void
    {
        $this->serviceManager = $serviceManager;
    }
}
