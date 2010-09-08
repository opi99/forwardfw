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
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

/**
 *
 */
require_once 'ForwardFW/Config.php';

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
class ForwardFW_Config_CacheSystem extends ForwardFW_Config
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
     * @var ForwardFW_Config_Cache_Frontend Configuration for Cache Frontend
     */
    private $arFrontendConfig = null;

    /**
     * @var ForwardFW_Config_Cache_Backend Configuration for Cache Backend
     */
    private $arBackendConfig = null;

    public function setCacheFrontend($strCacheFrontend)
    {
        $this->strCacheFrontend = $strCacheFrontend;
        return $this;
    }

    public function setCacheBackend($strCacheBackend)
    {
        $this->strCacheBackend = $strCacheBackend;
        return $this;
    }

    public function getCacheFrontend()
    {
        return $this->strCacheFrontend;
    }

    public function getCacheBackend()
    {
        return $this->strCacheBackend;
    }
}
?>