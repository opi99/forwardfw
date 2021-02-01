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
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

/**
 * This class represents the Request from browser.
 *
 * @category   Request
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Request
{
    /** @var string Path to route applications */
    protected $routePath = null;

    /** @var array data of json request */
    protected $json = null;

    /**
     * Returns the parameter for the application from browser request or
     * the session data.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $controllerClass Class name of the controller, wh asks.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The parameter for the application or null.
     */
    public function getParameter(
        $parameterName,
        $controllerClass = 'ForwardFW_Request',
        $applicationName = null
    ) {
        $return = $this->getRequestParameter(
            $parameterName,
            $applicationName
        );
        if ($return === null) {
            $return = $this->getJsonParameter(
                $parameterName,
                $applicationName
            );
        }
        if ($return === null) {
            $return = $this->getConfigParameter(
                $parameterName,
                $controllerClass,
                $applicationName
            );
        }

        return $return;
    }

    /**
     * Returns the request parameter from browser/user session.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The parameter from the request.
     */
    public function getRequestParameter(
        $parameterName,
        $applicationName = null
    ) {
        return $this->getParameterFromArray($_REQUEST, $parameterName, $applicationName);
    }

    /**
     * Gets the json request parameter
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The json parameter from the request.
     */
    public function getJsonParameter(
        $parameterName,
        $applicationName = null
    ) {
        if ($this->json === null) {
            $this->json = json_decode(file_get_contents("php://input"), true);
            if ($this->json === null) {
                $this->json = [];
            }
        }

        return $this->getParameterFromArray($this->json, $parameterName, $applicationName);
    }

    /**
     * Gets the value of the parameter
     *
     * @param array $parameters The array containing the data to search in
     * @param string $parameterName Name of the parameter to return
     * @param string $applicationName Name of the application
     *
     * @return mixed The value of the parameter if set otherwise null
     */
    protected function getParameterFromArray(
        array $parameters,
        $parameterName,
        $applicationName = null
    ) {
        $return = null;
        if ($applicationName !== null) {
            if (isset($parameters[$applicationName])) {
                $requestData = $parameters[$applicationName];
            }
        } else {
            $requestData = $parameters;
        }
        if (isset($requestData[$parameterName])) {
            $return = $requestData[$parameterName];
        }
        return $return;
    }

    /**
     * Returns the config parameter for the application from configuration.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $controllerClass Class name of the controller, wh asks.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The configuration for the application or null.
     */
    public function getConfigParameter(
        $parameterName,
        $controllerClass = 'ForwardFW_Request',
        $applicationName = null
    ) {
        $return = null;
        if (isset($GLOBALS[$parameterName])) {
            $return = $GLOBALS[$parameterName];
        }
        if (isset($GLOBALS['ForwardFW'][$parameterName])) {
            $return = $GLOBALS['ForwardFW'][$parameterName];
        }
        if ($applicationName !== null && isset($GLOBALS[$applicationName][$parameterName])) {
            $return = $GLOBALS[$applicationName][$parameterName];
        }
        if (isset($GLOBALS[$controllerClass][$parameterName])) {
            $return = $GLOBALS[$controllerClass][$parameterName];
        }
        if ($applicationName !== null && isset($GLOBALS[$applicationName][$controllerClass][$parameterName])) {
            $return = $GLOBALS[$applicationName][$controllerClass][$parameterName];
        }
        return $return;
    }

    /**
     * Returns the path for routing
     *
     * @return string Path to route on
     */
    public function getRoutePath()
    {
        if ($this->routePath === null) {
            $this->routePath = $this->findRoutePath();
        }

        return $this->routePath;
    }

    /**
     * Sets the path for routing
     *
     * @param string $routePath Path to route on
     */
    public function setRoutePath($routePath)
    {
        $this->routePath = $routePath;
    }

    /**
     * Find the path to route out of the server request vars
     *
     * @return string Path to route on.
     */
    protected function findRoutePath()
    {
        $hostPath = $this->getHostPath();
        if ($hostPath === '') {
            $routePath = $_SERVER['REQUEST_URI'];
        } else {
            $routePath = substr($_SERVER['REQUEST_URI'], strlen($hostPath) + 1);
        }

        return $routePath;
    }

    public function getHostPath()
    {
        $strPath = dirname($_SERVER['PHP_SELF']);
        if ($strPath === '/') {
            $strPath = '';
        }
        return $strPath;
    }

    public function getHostName()
    {
        return $_SERVER['HTTP_HOST'];
    }
}
