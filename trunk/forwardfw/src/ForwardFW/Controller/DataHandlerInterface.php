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
 * @category   DataLoader
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.7
 */

namespace ForwardFW\Controller;

require_once 'ForwardFW/Controller/ApplicationInterface.php';

/**
 * Interface for a DataLoader.
 *
 * @category   DataLoader
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface DataHandlerInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application);

    /**
     * Calls the loading if it isn't in cache or cache timed out.
     *
     * @param string  $strConnection Name of connection defined in conf.
     * @param array   $arOptions     Operations for this load.
     * @param integer $nCacheTimeout Cache lifetime, -1 to use default.
     *
     * @return mixed The response Data
     */
    public function loadFromCached(
        $strConnection, array $arOptions, $nCacheTimeout = -1
    );

    /**
     * Load method.
     *
     * @param string $strConnection Name of connection defined in conf.
     * @param array  $arOptions     Operations for this load.
     *
     * @return mixed The response Data
     */
    public function loadFrom($strConnection, array $arOptions);

    /**
     * Save method.
     *
     * @param string $strConnection Name of connection defined in conf.
     * @param array  $options       Operations for the saving.
     *
     * @return boolean 
     */
    public function saveTo($strConnection, array $options);

    /**
     * Initialize the given connection.
     *
     * @param string $strConnection Name of connection defined in conf.
     *
     * @return void
     */
    public function initConnection($strConnection);
}
