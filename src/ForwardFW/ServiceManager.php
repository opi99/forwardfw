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

use ForwardFW\Exception\ServiceManagerException;
use ForwardFW\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * This class represents available Services.
 */
class ServiceManager
    implements ContainerInterface
{
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
            throw new ServiceManagerException('Class already registered as service.');
        }

        $this->registeredServiceClasses[$className] = $config;

        if ($registerInterface) {
            $interfaceName = $config->getInterfaceName();

            $reflection = new \ReflectionClass($className);
            if ($reflection->implementsInterface($interfaceName)) {
                if (!isset($this->registeredServices[$interfaceName])) {
                    $this->registeredServices[$interfaceName] = $config;
                }
            } else {
                throw new ServiceManagerException('Class doesn\'t implement given interface.');
            }
        }

        if ($config->hasSubServices()) {
            $subServicesConfig = $config->getSubServicesConfig();
            foreach ($subServicesConfig as $subServiceConfig) {
                $this->registerService($subServiceConfig);
            }

        }
    }

    public function get(string $id)
    {
        if (isset($this->startedServicesByInterface[$id])) {
            return $this->startedServicesByInterface[$id];
        }

        if (isset($this->startedServicesByClass[$id])) {
            return $this->startedServicesByClass[$id];
        }

        if (isset($this->registeredServices[$id])) {
            return $this->createAndStartService($this->registeredServices[$id]);
        }

        if (isset($this->registeredServiceClasses[$id])) {
            return $this->createAndStartService($this->registeredServiceClasses[$id]);
        }

        throw new ServiceNotFoundException('Service "'. $id . '" not registered.');
    }

    public function has(string $id): bool
    {
        if (isset($this->startedServicesByInterface[$id])
            || isset($this->startedServicesByClass[$id])
            || isset($this->registeredServices[$id])
            || isset($this->registeredServiceClasses[$id])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Compatibility function to PSR-11 get function
     */
    public function getService($interfaceName)
    {
        return $this->get($interfaceName);
    }

    protected function createAndStartService(\ForwardFW\Config\Service $config)
    {
        $className = $config->getExecutionClassName();

        $class = new $className($config, $this);

        if ($class instanceof Service\Startable) {
            $class->start();
        }

        // Search if for given class an interface entry was registered, if yes, save this class in startedServicesByInterface
        $possibleInterfaceName = array_find_key($this->registeredServices, function ($serviceConfig) use ($className) {
            return $serviceConfig->getExecutionClassName() === $className;
        });
        if ($possibleInterfaceName) {
            $this->startedServicesByInterface[$possibleInterfaceName] = $class;
        }
        $this->startedServicesByClass[$className] = $class;

        return $class;
    }

    public function stopService($interfaceName): void
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
