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
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.11
 */

namespace ForwardFW\Config\Filter\RequestResponse;

/**
 * Config for a Application Filter.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Application extends \ForwardFW\Config\Filter\RequestResponse
{
    /**
     * @var string Class of application to call
     */
    protected $strExecutionClass = 'ForwardFW\\Filter\\RequestResponse\\Application';

    /**
     * @var ForwardFW\Config\Application Config of the application
     */
    private $config = '';

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $config Config of the RequestResponse filter.
     *
     * @return FForwardFW\Config\Filter\RequestResponse\Application
     */
    public function setConfig(\ForwardFW\Config\Application $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Application
     */
    public function getConfig()
    {
        return $this->config;
    }
}
