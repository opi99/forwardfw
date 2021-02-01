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

namespace ForwardFW\Service;

/**
 * This interface defines services which can be started.
 */
class AbstractService
{
    /** @var \ForwardFW\ServiceManager The ServiceManager instance. */
    protected $serviceManager;

    /** @var \ForwardFW\Config\Service The config for this service. */
    protected $config;

    /**
     * Constructor
     *
     * @param \ForwardFW\ServiceManager $response The ServiceManager instance.
     * @param \ForwardFW\Config\Service $config Config for the service, needs to be verified in the service.
     *
     * @return void
     */
    public function __construct(\ForwardFW\ServiceManager $serviceManager, \ForwardFW\Config\Service $config)
    {
        $this->serviceManager = $serviceManager;
        $this->config = $config;
    }

    /**
     * Returns the ServiceManager instance
     *
     * @return \ForwardFW\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
