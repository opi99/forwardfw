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
class RegisterServices extends \ForwardFW\Config\Filter\RequestResponse
{
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\RegisterServices';

    /**
     * @var ForwardFW\Config\Service[] Config of the routes
     */
    private $services = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \ArrayObject();
    }

    /**
     * Adding the config of a Route.
     *
     * @param ForwardFW\Config\Service $route The route config to add.
     * @return void
     */
    public function addService(\ForwardFW\Config\Service $route)
    {
        $this->services->append($route);
        return $this;
    }

    /**
     * Returns the configured routes.
     *
     * @return ForwardFW\Config\Service Config of routes
     */
    public function getServices()
    {
        return $this->services;
    }
}
