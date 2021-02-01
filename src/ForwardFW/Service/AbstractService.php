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
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.1
 */

namespace ForwardFW\Service;

/**
 * This interface defines services which can be started.
 *
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
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
