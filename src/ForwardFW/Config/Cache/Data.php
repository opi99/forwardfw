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
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

namespace ForwardFW\Config\Cache;

/**
 * Config for a Cache.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Data extends \ForwardFW\Config
{
    /**
     * @var integer Timeout of the cache in seconds.
     */
    protected $nTimeout = 0;

    /**
     * @var boolean Should old data be returned if an error occours on fetching data.
     */
    protected $bReserveOld = false;

    /**
     * Sets the timeout
     *
     * @param integer Timeout in seconds.
     *
     * @return ForwardFW_Config_CacheData
     */
    public function setTimeout($nTimeout)
    {
        $this->nTimeout = $nTimeout;
        return $this;
    }

    /**
     * Sets the flag if Old data should be used on error while fetching data.
     *
     * @param boolean $bReserveOld If old data should be reserved.
     *
     * @return ForwardFW_Config
     */
    public function setReserveOld($bReserveOld)
    {
        $this->bReserveOld = $bReserveOld;
        return $this;
    }

    /**
     * Gets the timeout
     *
     * @return integer The timeout in seconds.
     */
    public function getTimeout()
    {
        return $this->nTimeout;
    }

    /**
     * Gets the flag for old data handling.
     *
     * @return boolean State of the flag.
     */
    public function getReserveOld()
    {
        return $this->bReserveOld;
    }
}
