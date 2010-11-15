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
 * @copyright  2009, 2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

require_once 'ForwardFW/Config/CacheData.php';
require_once 'ForwardFW/Config/CacheSystem.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Cache/Backend.php';
require_once 'ForwardFW/Interface/Cache/Frontend.php';

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
abstract class ForwardFW_Cache implements ForwardFW_Interface_Cache_Frontend
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
     * @param ForwardFW_Config_CacheSystem    $config      Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Frontend The cache Frontend
     */
    static public function getInstance(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_CacheSystem $config
    ) {
        $backend = self::getBackend($application, $config);
        $frontend = self::getFrontend($application, $config, $backend);
        return $frontend;
    }

    /**
     * Builds Backend of a cache configuration
     *
     * @param ForwardFW_Interface_Application $application The running application
     * @param ForwardFW_Config_CacheSystem    $config      Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Backend Caching Backend.
     */
    static public function getBackend(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_CacheSystem $config
    ) {
        $class = $config->getCacheBackend();
        if (isset($GLOBALS['Cache']['backend'][$class])) {
            $return = $GLOBALS['Cache']['backend'][$class];
        } else {
            include_once str_replace('_', '/', $class) . '.php';
            $return = new $class($application, $backend);
            $GLOBALS['Cache']['backend'][$class] = $return;
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
    static public function getFrontend(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_CacheSystem $config,
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
        } catch (Exception $e) {
            $mData = $this->getDataToCache($config);
            $this->backend->setData($strHash, $mData);
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