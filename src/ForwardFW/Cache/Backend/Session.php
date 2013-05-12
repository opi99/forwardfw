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
 * @subpackage Cache/Backend
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

namespace ForwardFW\Cache\Backend;

/**
 * Implementation of a Cache Backend.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Cache/Backend
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Cache_Backend_Session extends ForwardFW_Cache_Backend
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend  $config      Backend config.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    ) {
        session_start();
        parent::__construct($application, $config);
    }

    /**
     * Writes data into the cache
     *
     *
     * @param string $strHash Hash for data.
     * @param array  $arData  Data to save into cache.
     *
     * @return void
     */
    protected function writeData($strHash, array $arData)
    {
        $_SESSION['cache'][$strHash] = $arData;
    }

    /**
     * Reads data from the cache
     *
     * @param string $strHash Hash for data.
     *
     * @return array Data from the storage
     */
    protected function readData($strHash)
    {
        return $_SESSION['cache'][$strHash];
    }

    /**
     * Removes data from the cache
     *
     * @param string $strHash Hash for data.
     *
     * @return boolean Returns true if data removed otherwise false.
     */
    protected function removeData($strHash)
    {
        unset($_SESSION['cache'][$strHash]);
        return true;
    }

    /**
     * Clear complete cache
     *
     * @return void
     */
    protected function clear($strHash)
    {
        $_SESSION['cache'] = array();
        return true;
    }
}
