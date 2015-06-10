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
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.11
 */

namespace ForwardFW\Config\Filter\RequestResponse\SimpleRouter;

/**
 * Config for a SimpleRouter Filter.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Route extends \ForwardFW\Config
{
    /**
     * @var string Startpoint of the route
     */
    private $start = '';

    /**
     * @var ForwardFW\Config\Filter\RequestResponse[] Config of the filter
     */
    private $filterConfigs = array();

    /**
     * Sets Startpoint of the route
     *
     * @param string $strStart Startpoint of the route
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter
     */
    public function setStart($strStart)
    {
        $this->strStart = $strStart;
        return $this;
    }

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $filterConfig Config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter
     */
    public function addFilterConfig(\ForwardFW\Config\Filter\RequestResponse $filterConfig)
    {
        $this->filterConfigs[] = $filterConfig;
        return $this;
    }

    /**
     * Get Startpoint of the route.
     *
     * @return string
     */
    public function getStart()
    {
        return $this->strStart;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse[]
     */
    public function getFilterConfigs()
    {
        return $this->filterConfigs;
    }
}
