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

namespace ForwardFW\Config\Filter\RequestResponse;

/**
 * Config for a SimpleRouter Filter.
 */
class SimpleRouter extends \ForwardFW\Config\Filter\RequestResponse
{
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\SimpleRouter';

    /**
     * @var ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route[] Config of the routes
     */
    private $routes = null;

    /**
     * @var boolean Set to false if the SimpleRouter shouldn't add an error if route was not found.
     */
    private $routeNotFoundError = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new \ArrayObject();
    }

    /**
     * Adding the config of a Route.
     *
     * @param ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route $route The route config to add.
     * @return $this
     */
    public function addRoute(SimpleRouter\Route $route)
    {
        $this->routes->append($route);
        return $this;
    }

    /**
     * Returns the configured routes.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route[] Config of routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set if a route not found error should be set or not.
     *
     * @param boolean $routeNotFoundError True if you want a route not found error otherwise false
     * @return $this
     */
    public function setRouteNotFoundError($routeNotFoundError = true)
    {
        $this->routeNotFoundError = $routeNotFoundError;
        return $this;
    }


    /**
     * Get if a route not found error should be set or not.
     *
     * @return boolean True if want a route not found error should be set otherwise false.
     */
    public function getRouteNotFoundError()
    {
        return $this->routeNotFoundError;
    }
}
