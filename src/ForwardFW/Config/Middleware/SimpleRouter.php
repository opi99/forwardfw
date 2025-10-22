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

namespace ForwardFW\Config\Middleware;

/**
 * Config for a SimpleRouter Filter.
 */
class SimpleRouter extends \ForwardFW\Config\Middleware
{
    protected string $executionClassName = \ForwardFW\Middleware\SimpleRouter::class;

    /** @var ForwardFW\Config\Middleware\SimpleRouter\Route[] Config of the routes */
    private $routes = [];

    /** @var boolean Set to false if the SimpleRouter shouldn't add an error if route was not found. */
    private $routeNotFoundError = true;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Adding the config of a Route.
     *
     * @param ForwardFW\Config\Middleware\SimpleRouter\Route $route The route config to add.
     */
    public function addRoute(SimpleRouter\Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Returns the configured routes.
     *
     * @return ForwardFW\Config\Middleware\SimpleRouter\Route[] Config of routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Set if a route not found error should be set or not.
     *
     * @param boolean $routeNotFoundError True if you want a route not found error otherwise false
     */
    public function setRouteNotFoundError(bool $routeNotFoundError = true): self
    {
        $this->routeNotFoundError = $routeNotFoundError;
        return $this;
    }


    /**
     * Get if a route not found error should be set or not.
     *
     * @return boolean True if want a route not found error should be set otherwise false.
     */
    public function getRouteNotFoundError(): bool
    {
        return $this->routeNotFoundError;
    }
}
