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
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
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
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Frontend extends \ForwardFW\Config
{
    /**
     * @var string Class Name of Cache Frontend
     */
    private $strCacheFrontend = '';

    /**
     * @var string Class Name of Cache Backend
     */
    private $strCacheBackend = '';

    /**
     * @var ForwardFW\Config\Cache\Backend Configuration for Cache Backend
     */
    private $backendConfig = null;

    /**
     * Sets Name of FE Cache
     *
     * @param string $strCacheFrontend Name of the frontend cache.
     *
     * @return ForwardFW_Config_Cache_Frontend This.
     */
    public function setCacheFrontend($strCacheFrontend)
    {
        $this->strCacheFrontend = $strCacheFrontend;
        return $this;
    }

    /**
     * Sets Name of BE Cache
     *
     * @param string $strCacheBackend Name of the backend cache.
     *
     * @return ForwardFW_Config_Cache_Frontend This.
     */
    public function setCacheBackend($strCacheBackend)
    {
        $this->strCacheBackend = $strCacheBackend;
        return $this;
    }

    /**
     * Config of the Backend
     *
     * @param ForwardFW\Config\Cache\Backend $backendConfig Config of the backend cache.
     *
     * @return ForwardFW\Config\Cache\Frontend This.
     */
    public function setBackendConfig(\ForwardFW\Config\Cache\Backend $backendConfig)
    {
        $this->backendConfig = $backendConfig;
        return $this;
    }

    /**
     * Get Name of cache frontend.
     *
     * @return string Name of frontend cache
     */
    public function getCacheFrontend()
    {
        return $this->strCacheFrontend;
    }

    /**
     * Get Name of cache backend.
     *
     * @return string Name of backend cache
     */
    public function getCacheBackend()
    {
        return $this->strCacheBackend;
    }


    /**
     * Get config of cache backend.
     *
     * @return ForwardFW_Config_Cache_Backend Config of backend cache
     */
    public function getBackendConfig()
    {
        return $this->backendConfig;
    }
}
