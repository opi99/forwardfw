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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.1
 */

namespace ForwardFW\Config\Filter\RequestResponse;

/**
 * Config for a SimpleRouter Filter.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class RegisterServices extends \ForwardFW\Config\Filter\RequestResponse
{
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\RegisterServices';

    /**
     * @var ForwardFW\Config\Service[] Config of the routes
     */
    private $services = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \ArrayObject();
    }

    /**
     * Adding the config of a Route.
     *
     * @param ForwardFW\Config\Service $route The route config to add.
     * @return void
     */
    public function addService(\ForwardFW\Config\Service $route)
    {
        $this->services->append($route);
        return $this;
    }

    /**
     * Returns the configured routes.
     *
     * @return ForwardFW\Config\Service Config of routes
     */
    public function getServices()
    {
        return $this->services;
    }
}
