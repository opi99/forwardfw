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
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
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
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Request
{
    /** @var string Path to route applications */
    protected $routePath = null;

    /**
     * Returns the parameter for the application from browser request or
     * the session data.
     *
     * @param string $strParameterName   Name of the parameter to return.
     * @param string $strControllerClass Class name of the controller, wh asks.
     * @param string $strApplicationName Name of the application.
     *
     * @return mixed The parameter for the application or null.
     */
    public function getParameter(
        $strParameterName,
        $strControllerClass = 'ForwardFW_Request',
        $strApplicationName = ''
    ) {
        $return = $this->getRequestParameter(
            $strParameterName,
            $strApplicationName
        );
        if (is_null($return)) {
            $return = $this->getConfigParameter(
                $strParameterName,
                $strControllerClass,
                $strApplicationName
            );
        }
        return $return;
    }

    /**
     * Returns the request parameter from browser/user session.
     *
     * @param string $strParameterName   Name of the parameter to return.
     * @param string $strApplicationName Name of the application.
     *
     * @return mixed The parameter from the request.
     */
    public function getRequestParameter(
        $strParameterName,
        $strApplicationName = ''
    ) {
        $return = null;
        if ($strApplicationName) {
            $data = $_REQUEST[$strApplicationName];
        } else {
            $data = $_REQUEST;
        }
        if (isset($data[$strParameterName])) {
            $return = $data[$strParameterName];
        }
        return $return;
    }

    /**
     * Returns the config parameter for the application from configuration.
     *
     * @param string $strParameterName   Name of the parameter to return.
     * @param string $strControllerClass Class name of the controller, wh asks.
     * @param string $strApplicationName Name of the application.
     *
     * @return mixed The configuration for the application or null.
     */
    public function getConfigParameter(
        $strParameterName,
        $strControllerClass = 'ForwardFW_Request',
        $strApplicationName = ''
    ) {
        $return = null;
        if (isset($GLOBALS[$strParameterName])) {
            $return = $GLOBALS[$strParameterName];
        }
        if (isset($GLOBALS['ForwardFW'][$strParameterName])) {
            $return = $GLOBALS['ForwardFW'][$strParameterName];
        }
        if (isset($GLOBALS[$strApplicationName][$strParameterName])) {
            $return = $GLOBALS[$strApplicationName][$strParameterName];
        }
        if (isset($GLOBALS[$strControllerClass][$strParameterName])) {
            $return = $GLOBALS[$strControllerClass][$strParameterName];
        }
        if (isset($GLOBALS[$strApplicationName][$strControllerClass][$strParameterName])) {
            $return = $GLOBALS[$strApplicationName][$strControllerClass][$strParameterName];
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
        if ($this->routePath === NULL) {
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
