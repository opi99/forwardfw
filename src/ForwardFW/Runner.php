<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category   Request
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
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
