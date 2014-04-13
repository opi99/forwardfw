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
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

namespace ForwardFW\Cache;

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
abstract class Frontend implements FrontendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Cache\BackendInterface $backend Backend instance.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Cache\BackendInterface $backend
    ) {
        $this->application = $application;
        $this->backend = $backend;
    }

    /**
     * Builds an instance of cache
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Config\Cache\Frontend $config Configuration of caching
     *
     * @return ForwardFW\Cache\FrontendInterface The cache Frontend
     */
    public static function getInstance(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Frontend $config
    ) {
        $backend = self::getBackend(
            $application,
            $config->getBackendConfig()
        );
        $frontend = self::getFrontend($application, $config, $backend);
        return $frontend;
    }

    /**
     * Builds Backend of a cache configuration
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Config\Cache\Backend $config Configuration of caching
     *
     * @return ForwardFW\Cache\BackendInterface Caching Backend.
     */
    public static function getBackend(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    ) {
        $className = $config->getExecutionClass();
        return new $className($application, $config);
    }

    /**
     * Builds Backend of a cache configuration
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Config\Cache\Frontend $config Configuration of caching
     * @param ForwardFW\Cache\BackendInterface $backend Backend for the frontend
     *
     * @return ForwardFW\Cache\FrontendInterface Caching Frontend.
     */
    public static function getFrontend(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Frontend $config,
        \ForwardFW\Cache\BackendInterface $backend
    ) {
        $className = $config->getExecutionClass();
        return new $className($application, $backend);
    }

    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW\Config\Cache\Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(\ForwardFW\Config\Cache\Data $config)
    {
        $hash = $this->calculateHash($config);
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
            $data = $this->backend->getData($hash, $nTime);
        } catch (\ForwardFW\Cache\Exception\NoData $eNoData) {
            $data = $this->getRealData($hash, $config, false);
        } catch (\ForwardFW\Cache\Exception\TimeOut $eTimeOut) {
            $data = $this->getRealData($hash, $config, true);
        } catch (\ForwardFW\Cache\Exception\IsGenerating $eIsGenerating) {
            usleep(500);
            $data = $this->getCache($config);
        }

        return $data;
    }


    /**
     * Returns the real data and add it to cache. If real data fails tries to
     * get old data from cache if available.
     *
     * @param string $hash Hash of cache
     * @param ForwardFW\Config\Cache\Data $config What data should be get from cache.
     * @param boolean $bOldAvailable True if backend has old data for hash.
     *
     * @return mixed The data you requested.
     */
    protected function getRealData(
        $hash,
        \ForwardFW\Config\Cache\Data $config,
        $isOldAvailable
    ) {
        try {
            $mData = $this->getDataToCache($config);
            $this->backend->setData($hash, $mData);
        } catch (\Exception $e) {
            if ($config->getReserveOld() && $isOldAvailable) {
                $mData = $this->backend->getData($hash, 0);
            }
        }

        return $mData;
    }

    abstract protected function calculateHash(\ForwardFW\Config\Cache\Data $config);

    abstract protected function getDataToCache(\ForwardFW\Config\Cache\Data $config);

    /**
     * Calculates a hash by serialize and md5.
     *
     * @param mixed $data The data from which the hash should be gathered.
     *
     * @return string The md5 hash.
     */
    public function getHash($data)
    {
        return md5(serialize($data));
    }
}
