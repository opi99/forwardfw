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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Config;

/**
 * Config for the Runner.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Runner extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var string Class Name of executor
     */
    protected $executionClassName = 'ForwardFW\\Runner';

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
