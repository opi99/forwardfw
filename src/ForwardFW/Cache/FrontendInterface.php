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
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

namespace ForwardFW\Cache;

require_once 'ForwardFW/Cache/BackendInterface.php';
require_once 'ForwardFW/Config/Cache/Data.php';
require_once 'ForwardFW/Config/Cache/Frontend.php';
require_once 'ForwardFW/Controller/ApplicationInterface.php';

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
interface FrontendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface   $application The running application.
     * @param ForwardFW\Cache\BackendInterface $backend     Backend for storing
     *                                                       cache data.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Cache\BackendInterface $backend
    );

    /**
     * Builds an instance of cache
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Config\Cache\Frontend $config      Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Frontend The cache Frontend
     */
    static public function getInstance(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Frontend $config
    );

    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW\Config\Cache\Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(
        \ForwardFW\Config\Cache\Data $config
    );
}
