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

namespace ForwardFW\Config;

/**
 * Config for the Runner.
 */
class Runner extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var string Class Name of executor
     */
    protected $executionClassName = 'ForwardFW\\Runner';

    /**
     * @var boolean True if runner should send data otherwise false
     */
    private $shouldSend = true;

    /**
     * @var \ForwardFW\Config\Service Config of the services
     */
    private $services;

    /**
     * @var \ForwardFW\Config\Processor Config of the processors
     */
    private $processors;

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
        $this->processors = new \ArrayObject();
    }

    public function setShouldSend($shouldSend)
    {
        $this->shouldSend = $shouldSend;
        return $this;
    }


    public function getShouldSend()
    {
        return $this->shouldSend;
    }

    /**
     * Adding the config of a service.
     *
     * @param ForwardFW\Config\Service $service The service config to add
     * @return void
     */
    public function addService(\ForwardFW\Config\Service $service)
    {
        $this->services->append($service);
        return $this;
    }

    /**
     * Returns the configured services.
     *
     * @return ForwardFW\Config\Service[] Config of services
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Adding the config of a processor.
     *
     * @param ForwardFW\Config\Processor $processor The processor config to add
     * @return void
     */
    public function addProcessor(\ForwardFW\Config\Processor $processor)
    {
        $this->processors->append($processor);
        return $this;
    }

    /**
     * Returns the configured processors.
     *
     * @return ForwardFW\Config\Processor[] Config of processors
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Sets the config of the service manager
     *
     * @param ForwardFW\Config\ServiceManager $serviceManager The config to the service manager
     * @return void
     */
    public function setServiceManager(\ForwardFW\Config\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Returns the config of the service manager.
     *
     * @return \ForwardFW\Config\ServiceManager Config of processors
     */
    public function getServiceManager()
    {
        if ($this->serviceManager === null) {
            $this->serviceManager = new ServiceManager();
        }
        return $this->serviceManager;
    }
}
