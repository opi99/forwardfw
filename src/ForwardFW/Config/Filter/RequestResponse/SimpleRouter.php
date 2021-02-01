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
 * @since      File available since Release 0.1.0
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
class SimpleRouter extends \ForwardFW\Config\Filter\RequestResponse
{
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\SimpleRouter';

    /**
     * @var ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route[] Config of the routes
     */
    private $routes = null;

    /**
     * @var boolean Set to false if the SimpleRouter shouldn't add an error if route was not found.
     */
    private $routeNotFoundError = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new \ArrayObject();
    }

    /**
     * Adding the config of a Route.
     *
     * @param ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route $route The route config to add.
     * @return $this
     */
    public function addRoute(SimpleRouter\Route $route)
    {
        $this->routes->append($route);
        return $this;
    }

    /**
     * Returns the configured routes.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter\Route[] Config of routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Set if a route not found error should be set or not.
     *
     * @param boolean $routeNotFoundError True if you want a route not found error otherwise false
     * @return $this
     */
    public function setRouteNotFoundError($routeNotFoundError = true)
    {
        $this->routeNotFoundError = $routeNotFoundError;
        return $this;
    }


    /**
     * Get if a route not found error should be set or not.
     *
     * @return boolean True if want a route not found error should be set otherwise false.
     */
    public function getRouteNotFoundError()
    {
        return $this->routeNotFoundError;
    }
}
