<?php
declare(encoding = "utf-8");
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
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

require_once 'ForwardFW/Config/FunctionCacheData.php';
require_once 'ForwardFW/Config/CacheSystem.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Cache/Backend.php';

/**
 * Implementation of a Cache Backend.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Cache_Backend_Session implements ForwardFW_Interface_Cache_Backend
{
    public function __construct(
        ForwardFW_Interface_Application $application
    ) {
        session_start();
        $this->application = $application;
    }

    public function getInstance(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_CacheSystem $config
    ) {
        // Not realy?
    }

    /**
     * Gets data from Cache.
     *
     * @param string $strHash Hash for data.
     * @param integer $nTime   Oldest Time of data in cache.
     *
     * @return mixed Data from cache
     */
    public function getData($strHash, $nTime)
    {
        if (isset($_SESSION[$strHash])) {
            if ($_SESSION[$strHash]['time'] > $nTime) {
                return $_SESSION[$strHash]['data'];
            } else {
                // Data but timed out exception
                throw new Exception();
            }
        } else {
            // No Data Exception
            throw new Exception();
        }
    }


    /**
     * Sets data from Cache.
     *
     * @param string $strHash Hash for data.
     * @param mixed  $mData   Data to save into cache.
     *
     * @return void
     */
    public function setData($strHash, $mData)
    {
        $_SESSION[$strHash] = array(
            'data' => $mData,
            'time' => time(),
        );
    }
}
?>