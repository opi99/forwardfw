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
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.11
 */

namespace ForwardFW\Config\Service;

/**
 * Config for a Service.
 *
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class DataHandler extends \ForwardFW\Config\Service
{
    protected $executionClassName = 'ForwardFW\\Controller\\DataHandler';

    protected $interfaceName = 'ForwardFW\\Controller\\DataHandlerInterface';

    // No different DataHandler configurable yet. Maybe remove DataHandler and declare this as different startable services?
    /** @var string prefix in tables. */
    private $tablePrefix = '';

    /**
     * Sets prefix for the used tables.
     *
     * @param string $tablePrefix Prefix for Tables.
     *
     * @return ForwardFW\Config\Service
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * Gets prefix for the used tables.
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }
}
