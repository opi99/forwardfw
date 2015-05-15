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
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.10
 */

namespace ForwardFW\Filter\RequestResponse;

/**
 * This class loads and runs the requested Application.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class SimpleRouter extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Start Route');
        $parent = $this;

        $routePath = $this->getRoutePath();

        foreach ($this->config->getRoutes() as $routeConfig) {
            if (strncmp($routePath, $routeConfig->getStart(), strlen($routeConfig->getStart())) === 0) {
                $strFilter = $routeConfig->getFilterClass();
                $child = new $strFilter(null, $routeConfig->getFilterConfig(), $this->request, $this->response);
                $parent->setChild($child);
                $parent = $child;
                break;
            }
        }
        if ($this->child === null) {
            $this->response->addError('No Route "' . $routePath . '" found');
        }
    }

    /**
     * Returns the path for routing
     *
     * @return strin Path to route on
     */
    protected function getRoutePath()
    {
        if ($this->config->getRequestPath()) {
            $routePath = $this->config->getRequestPath();
        } else {
            $strPath = dirname($_SERVER['PHP_SELF']);
            if ($strPath === '/') {
                $routePath = $_SERVER['REQUEST_URI'];
            } else {
                $routePath = substr($_SERVER['REQUEST_URI'], strlen($strPath));
            }
        }

        return $routePath;
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('End Route');
    }
}
