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
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

/**
 *
 */
require_once 'ForwardFW/Config/CacheData.php';
require_once 'ForwardFW/Config/CacheSystem.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Cache.php';

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
class ForwardFW_Cache implements ForwardFW_Interface_Cache
{
    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application $application The running application
     *
     * @return void
     */
    public function __construct(
        ForwardFW_Interface_Application $application
    ) {
        $this->application = $application;
    }

    public function getInstance(
        ForwardFW_Interface_Application $application,
        ForwardFW_Config_CacheSystem $config
    ) {
        if (isset($GLOBALS['Cache']['instance'][$application])) {
            $return = $GLOBALS['Cache']['instance'][$application->getName()];
        } else {
            $return = new $config->strCacheFrontend($application);
            $GLOBALS['Cache']['instance'][$application->getName()] = $return;
        }
        return $return;
    }

    public function getCache(
        ForwardFW_Config_CacheData $config = null
    ) {
        return 'getCache';
    }
}
?>