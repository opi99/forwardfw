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

require_once 'ForwardFW/Config/CacheData.php';
require_once 'ForwardFW/Config/Cache/Frontend.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Cache/Backend.php';
require_once 'ForwardFW/Interface/Cache/Frontend.php';

require_once 'ForwardFW/Cache/Exception/TimeOut.php';
require_once 'ForwardFW/Cache/Exception/NoData.php';
require_once 'ForwardFW/Cache/Exception/IsGenerating.php';

/**
 * Interface for a Cache.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
abstract class ForwardFW_Cache_Frontend implements ForwardFW_Interface_Cache_Frontend
{
    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application   $application The running application
     * @param ForwardFW_Interface_Cache_Backend $backend     Backend instance.
     *
     * @return void
     */
    public function __construct(
        ForwardFW_Interface_Application $application,
        ForwardFW_Interface_Cache_Backend $backend
    ) {
        $this->application = $application;
        $this->backend = $backend;
    }

    /**
     * Builds an instance of cache
     *
     * @param ForwardFW_Interface_Application $application The running application
     * @param ForwardFW_Config_Cache_Frontend $config      Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Frontend The cache Frontend
     */
    public static function getInstance(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_Cache_Frontend $config
    ) {
        $backend = self::getBackend(
            $application,
            $config->getCacheBackend(),
            $config->getBackendConfig()
        );
        $frontend = self::getFrontend($application, $config, $backend);
        return $frontend;
    }

    /**
     * Builds Backend of a cache configuration
     *
     * @param ForwardFW_Interface_Application $application     The running application
     * @param string                          $strCacheBackend Configuration of caching
     * @param ForwardFW_Config_Cache_Backend  $config          Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Backend Caching Backend.
     */
    public static function getBackend(
        ForwardFW_Interface_Application $application,
        $strCacheBackend,
        ForwardFW_Config_Cache_Backend $config
    ) {
        if (isset($GLOBALS['Cache']['backend'][$strCacheBackend])) {
            $return = $GLOBALS['Cache']['backend'][$strCacheBackend];
        } else {
            include_once str_replace('_', '/', $strCacheBackend) . '.php';
            $return = new $strCacheBackend($application, $config);
            $GLOBALS['Cache']['backend'][$strCacheBackend] = $return;
        }
        return $return;
    }

    /**
     * Builds Backend of a cache configuration
     *
     * @param ForwardFW_Interface_Application   $application The running application
     * @param ForwardFW_Config_CacheSystem      $config      Configuration of caching
     * @param ForwardFW_Interface_Cache_Backend $backend     Backend for the frontend
     *
     * @return ForwardFW_Interface_Cache_Frontend Caching Frontend.
     */
    public static function getFrontend(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_Cache_Frontend $config,
        ForwardFW_Interface_Cache_Backend $backend
    ) {
        $class = $config->getCacheFrontend();
        if (isset($GLOBALS['Cache']['frontend'][$class])) {
            $return = $GLOBALS['Cache']['frontend'][$class];
        } else {
            include_once str_replace('_', '/', $class) . '.php';
            $return = new $class($application, $backend);
            $GLOBALS['Cache']['frontend'][$class] = $return;
        }
        return $return;
    }

    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW_Config_CacheData $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(ForwardFW_Config_CacheData $config)
    {
        $strHash = $this->calculateHash($config);
        switch ($config->getTimeout()) {
        case -1:
            $nTime = 0;
            break;
        case 0:
            $nTime = time();
            break;
        default:
            $nTime = time() - $config->getTimeout();
        }
        try {
            $mData = $this->backend->getData($strHash, $nTime);
        } catch (ForwardFW_Cache_Exception_NoData $eNoData) {
            $mData = $this->getRealData($strHash, $config, false);
        } catch (ForwardFW_Cache_Exception_TimeOut $eTimeOut) {
            $mData = $this->getRealData($strHash, $config, true);
        } catch (ForwardFW_Cache_Exception_IsGenerating $eIsGenerating) {
            usleep(500);
            $mData = $this->getCache($config);
        }

        return $mData;
    }


    /**
     * Returns the real data and add it to cache. If real data fails tries to
     * get old data from cache if available.
     *
     * @param String                     $strHash       Hash of cache
     * @param ForwardFW_Config_CacheData $config        What data should be get
     *                                                  from cache.
     * @param boolean                    $bOldAvailable True if backend has old
     *                                                  data for hash.
     *
     * @return mixed The data you requested.
     */
    protected function getRealData(
        $strHash, 
        ForwardFW_Config_CacheData $config,
        $bOldAvailable
    ) {
        try {
            $mData = $this->getDataToCache($config);
            $this->backend->setData($strHash, $mData);
        } catch (Exception $e) {
            if ($config->getReserveOld() && $bOldAvailable) {
                $mData = $this->backend->getData($strHash, 0);
            }
        }

        return $mData;
    }

    abstract protected function calculateHash(ForwardFW_Config_CacheData $config);

    abstract protected function getDataToCache(ForwardFW_Config_CacheData $config);

    /**
     * Calculates a hash by serialize and md5.
     *
     * @param mixed $mValue The data from which the hash should be gathered.
     *
     * @return string The hash.
     */
    public function getHash($mValue)
    {
        return md5(serialize($mValue));
    }
}
?>