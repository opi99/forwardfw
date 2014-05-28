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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.7
 */

namespace ForwardFW\Controller;

/**
 * Managing DataLoading via PEAR::MDB
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface DataHandlerInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application);

    /**
     * Returns an instance of configured DataHandler.
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public static function getInstance(ApplicationInterface $application);

    /**
     * Loads Data from cache or from a connection (DB, SOAP, File) if cache failed.
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFromCached($strConnection, array $arOptions, $nCacheTimeout = -1);

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($strConnection, array $arOptions);

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function create($strConnection, array $arOptions);

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($strConnection, array $arOptions);

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $strConnection Name of connection
     *
     * @return void
     */
    public function initConnection($strConnection);
}
