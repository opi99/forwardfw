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

namespace ForwardFW\Config;

/**
 * Config for the Runner.
 */
class Runner extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /** @var string Class Name of executor */
    protected $executionClassName = \ForwardFW\Runner::class;

    /**
     * @var boolean True if runner should send data otherwise false
     */
    private $shouldSend = true;

    /**
     * @var \ArrayObject<int, \ForwardFW\Config\Service> Config of the services
     */
    private $services;

    /**
     * @var \ArrayObject<int, \ForwardFW\Config\Middleware> Config of the middlewares
     */
    private $middlewares;

    /**
     * @var \ForwardFW\Config\ServiceManager Config of the service manager
     */
    private $serviceManager;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \ArrayObject();
        $this->middlewares = new \ArrayObject();
    }

    public function setShouldSend(bool $shouldSend): self
    {
        $this->shouldSend = $shouldSend;
        return $this;
    }


    public function getShouldSend(): bool
    {
        return $this->shouldSend;
    }

    /**
     * Adding the config of a service.
     *
     * @param \ForwardFW\Config\Service $service The service config to add
     */
    public function addService(\ForwardFW\Config\Service $service): self
    {
        $this->services->append($service);
        return $this;
    }

    /**
     * Returns the configured services.
     *
     * @return \ArrayObject<int, \ForwardFW\Config\Service> Config of services
     */
    public function getServices(): \ArrayObject
    {
        return $this->services;
    }

    /**
     * @param \ForwardFW\Config\Middleware $middleware The middleware config to add
     */
    public function addMiddleware(\ForwardFW\Config\Middleware $middleware): self
    {
        $this->middlewares->append($middleware);
        return $this;
    }

    /**
     * @return \ArrayObject<int, \ForwardFW\Config\Middleware> Config of middlewares
     */
    public function getMiddlewares(): \ArrayObject
    {
        return $this->middlewares;
    }

    /**
     * Sets the config of the service manager
     *
     * @param \ForwardFW\Config\ServiceManager $serviceManager The config to the service manager
     */
    public function setServiceManager(\ForwardFW\Config\ServiceManager $serviceManager): self
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Returns the config of the service manager.
     *
     * @return \ForwardFW\Config\ServiceManager Config of processors
     */
    public function getServiceManager(): \ForwardFW\Config\ServiceManager
    {
        if ($this->serviceManager === null) {
            $this->serviceManager = new ServiceManager();
        }
        return $this->serviceManager;
    }
}
