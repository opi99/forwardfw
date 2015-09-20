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
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.1
 */

namespace ForwardFW;

/**
 * This class represents available Services.
 *
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ServiceManager
{
    /**
     * @var \ForwardFW\Request The request instance
     */
    protected $request;

    /**
     * @var \ForwardFW\Response The response instance
     */
    protected $response;

    /**
     * @var \ForwardFW\Config\ServiceManager The config for the service manager
     */
    protected $config;

    private $registeredServices = array();

    private $startedServices = array();

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
        \ForwardFW\Config\ServiceManager $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response
    ) {
        $this->request = $request;
        $this->response = $response;
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

        $class = new $className($this, $config);

        if ($class instanceof Service\Startable) {
            $class->start();
            $this->startedServices[$interfaceName] = $class;
        }

        return $class;
    }

    public function stopService($interfaceName)
    {
        if (isset($this->startedServices[$interfaceName])) {
            $this->startedServices[$interfaceName]->stop();
            unset($this->startedServices[$interfaceName]);
        }
    }

    /**
     * Returns the request instance
     *
     * @return \ForwardFW\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response instance
     *
     * @return \ForwardFW\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
