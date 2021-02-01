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

namespace ForwardFW;

class Runner
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var \ForwardFW\Config\Runner
     */
    protected $config;

    public function __construct(
        \ForwardFW\Config\Runner $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response
    ) {
        $this->config = $config;
        $this->request  = $request;
        $this->response = $response;
    }

    public function run()
    {
        $this->initializeServiceManager();
        $this->registerServices();
        $this->runProcessors();
        $this->stopServices();
    }

    protected function initializeServiceManager()
    {
        $serviceManagerConfig = $this->config->getServiceManager();
        $class = $serviceManagerConfig->getExecutionClassName();
        $this->serviceManager = new $class($serviceManagerConfig, $this->request, $this->response);
    }

    protected function registerServices()
    {
        $this->response->addLog('Register Services');

        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->registerService($serviceConfig);
        }
    }

    protected function runProcessors()
    {
        ob_start();
        Filter\RequestResponse::getFilters($this->request, $this->response, $this->serviceManager, $this->config->getProcessors())
            ->doFilter();

        if ($this->config->getShouldSend()) {
            $this->response->send();
            ob_end_flush();
        } else {
            ob_end_clean();
        }
    }

    protected function stopServices()
    {
        $this->response->addLog('Stop Services');
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->serviceManager->stopService($serviceConfig->getInterfaceName());
        }
    }
}
