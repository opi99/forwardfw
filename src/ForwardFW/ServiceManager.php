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

    private $startedServices = [];

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

    public function registerService(\ForwardFW\Config\Service $config)
    {
        $className = $config->getExecutionClassName();
        $interfaceName = $config->getInterfaceName();

        $reflection = new \ReflectionClass($className);
        if ($reflection->implementsInterface($interfaceName)) {
            $this->registeredServices[$interfaceName] = $config;
        } else {
            throw new \Exception('Class doesn\'t implement given interface.');
        }
    }

    public function getService($interfaceName)
    {
        if (isset($this->startedServices[$interfaceName])) {
            return $this->startedServices[$interfaceName];
        }

        if (isset($this->registeredServices[$interfaceName])) {
            return $this->createAndStartService($interfaceName);
        }

        throw new \Exception('Service not registered.');
    }

    protected function createAndStartService($interfaceName)
    {
        $config = $this->registeredServices[$interfaceName];

        $className = $config->getExecutionClassName();

        $class = new $className($config, $this);

        if ($class instanceof Service\Startable) {
            $class->start();
        }
        $this->startedServices[$interfaceName] = $class;

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
