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
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009,2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

/**
 *
 */
require_once 'ForwardFW/Config/Cache/Data.php';
require_once 'ForwardFW/Config/Cache/Frontend.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Cache/Backend.php';

/**
 * Interface for a Cache.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface ForwardFW_Interface_Cache_Frontend
{
    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application   $application The running application.
     * @param ForwardFW_Interface_Cache_Backend $backend     Backend for storing
     *                                                       cache data.
     */
    public function __construct(
        ForwardFW_Interface_Application $application,
        ForwardFW_Interface_Cache_Backend $backend
    );

    /**
     * Builds an instance of cache
     *
     * @param ForwardFW_Interface_Application $application The running application
     * @param ForwardFW_Config_Cache_Frontend $config      Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Frontend The cache Frontend
     */
    static public function getInstance(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_Cache_Frontend $config
    );

    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW_Config_Cache_Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(
        ForwardFW_Config_Cache_Data $config
    );
}
?>