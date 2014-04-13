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
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface BackendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend  $config      Configuration of Backend.
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    );

    /**
     * Gets data from Cache.
     *
     * @param string  $strHash Hash for data.
     * @param integer $nTime   Oldest Time of data in cache.
     *
     * @return mixed Data from cache
     */
    public function getData($strHash, $nTime);

    /**
     * Sets data from Cache.
     *
     * @param string $strHash Hash for data.
     * @param mixed  $mData   Data to save into cache.
     *
     * @return void
     */
    public function setData($strHash, $mData);

    /**
     * Clears data from Cache.
     *
     * @param string $strHash Hash for data.
     *
     * @return void
     */
    public function unsetData($strHash);

    /**
     * Sets marker that cache will be generated yet.
     *
     * @param string $strHash Hash of cache which is generated.
     *
     * @return void
     */
    public function setGenerating($strHash);
}
