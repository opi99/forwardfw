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

/*
 * implements RequestHandlerInterface
 */
trait MiddlewareIteratorTrait
{
    /** @var \ArrayIterator */
    protected $middlewareIterator;

    protected function setMiddlewareIterator(\ArrayIterator $middlewareIterator): void
    {
        $this->middlewareIterator = $middlewareIterator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middlewareConfig = $this->middlewareIterator->current();
        $this->middlewareIterator->next();

        if ($middlewareConfig !== null) {
            $middlewareClass = $middlewareConfig->getExecutionClassName();
            $middleware = new $middlewareClass($middlewareConfig, $this->serviceManager);
            return $middleware->process($request, $this);
        } else {
            // No Middleware which runs?
            $factory = new ResponseFactory();
            return $factory->createResponse();
        }
    }
}
