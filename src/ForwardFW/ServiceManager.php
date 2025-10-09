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

/**
 * This class represents available Services.
 */
class ServiceManager
{
    /** @var \ForwardFW\Request The request instance */
    protected $request;

    /**
     * @var \ForwardFW\Response The response instance
     */
    protected $response;

    /** @var \ForwardFW\Config\ServiceManager The config for the service manager */
    protected $config;

    private $registeredServices = [];

    private $registeredServiceClasses = [];

    private $startedServicesByInterface = [];

    private $startedServicesByClass = [];

    /**
     * Constructor
     *
     * @param \ForwardFW\Config\ServiceManager $config Config of this ServiceManager
     * @param \ForwardFW\Request $request The request instance
     * @param \ForwardFW\Response $response The request instance
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\ServiceManager $config
    ) {
        $this->config = $config;
    }

    public function registerService(\ForwardFW\Config\Service $config, bool $registerInterface = true)
    {
        $className = $config->getExecutionClassName();

        if (isset($this->registeredServiceClasses[$className])) {
            throw new \Exception('Class already registered as service.');
        }

        $this->registeredServiceClasses[$className] = $config;

        if ($registerInterface) {
            $interfaceName = $config->getInterfaceName();

            $reflection = new \ReflectionClass($className);
            if ($reflection->implementsInterface($interfaceName)) {
                $this->registeredServices[$interfaceName] = $config;
            } else {
                throw new \Exception('Class doesn\'t implement given interface.');
            }
        }
    }

    public function getService($interfaceName)
    {
        if (isset($this->startedServicesByInterface[$interfaceName])) {
            return $this->startedServicesByInterface[$interfaceName];
        }

        if (isset($this->startedServicesByClass[$interfaceName])) {
            return $this->startedServicesByClass[$interfaceName];
        }

        if (isset($this->registeredServices[$interfaceName])) {
            return $this->createAndStartService($this->registeredServices[$interfaceName]);
        }

        if (isset($this->registeredServiceClasses[$interfaceName])) {
            return $this->createAndStartService($this->registeredServiceClasses[$interfaceName]);
        }

        throw new \Exception('Service "'. $interfaceName . '" not registered.');
    }

    protected function createAndStartService(\ForwardFW\Config\Service $config)
    {
        $className = $config->getExecutionClassName();

        $class = new $className($config, $this);

        if ($class instanceof Service\Startable) {
            $class->start();
        }
        $this->startedServicesByInterface[$interfaceName] = $class;
        $this->startedServicesByClass[$className] = $class;

        return $class;
    }

    public function stopService($interfaceName)
    {
        if (isset($this->startedServices[$interfaceName])) {
            $class = $this->startedServices[$interfaceName];
            if ($class instanceof Service\Startable) {
                $class->stop();
            }
            unset($this->startedServices[$interfaceName]);
        }
    }
}
