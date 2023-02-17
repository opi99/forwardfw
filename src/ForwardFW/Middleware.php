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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This abstract class needs to be extended to be a callable middleware.
 */
abstract class Middleware
    implements MiddlewareInterface
{
    protected ?\ForwardFW\Config $config = null;

    protected ?\ForwardFW\ServiceManager $serviceManager = null;

    public function __construct(
        \ForwardFW\Config $config,
        // We have no DI yet
        \ForwardFW\ServiceManager $serviceManager
    ) {
        $this->config = $config;
        $this->serviceManager = $serviceManager;
    }

    abstract public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
