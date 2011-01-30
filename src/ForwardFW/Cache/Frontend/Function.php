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

require_once 'ForwardFW/Cache/Frontend.php';
//require_once 'ForwardFW/Config/Cache/Frontend.php';
require_once 'ForwardFW/Config/Cache/Data/Function.php';
require_once 'ForwardFW/Interface/Application.php';

/**
 * Implementation of a Cache.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Cache_Frontend_Function extends ForwardFW_Cache_Frontend
{
    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW_Config_Cache_Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(ForwardFW_Config_Cache_Data $config) {
        return parent::getCache($config);
    }

    /**
     * Returns hash for this config.
     *
     * @param ForwardFW_Config_Cache_Data $config For what the hash should calculated.
     *
     * @return string Hash for the config.
     */
    protected function calculateHash(ForwardFW_Config_Cache_Data  $config)
    {
        return $this->getHash($config->getCallback()->getParameters());
    }

    protected function getDataToCache(ForwardFW_Config_Cache_Data $config)
    {
        return $config->getCallback()->doCallback();
    }
}
?>