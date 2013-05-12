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
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.2
 */

namespace ForwardFW;

/**
 * This class can instantiate a templater class.
 *
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Templater
{
    /*
     * Array of the application independent Factories
     */
    static private $instance = null;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Factory method to get Templater from config.
     *
     * @param ForwardFW\Controller\Application $application The running application
     *
     * @return ForwardFW\Templater
     */
    public static function factory(
        Controller\Application $application
    ) {
        if (is_null(self::$instance)) {
            self::$instance = static::createTemplater($application);
        }
        return self::$instance;
    }

    /**
     * Creation method of Templater from config.
     *
     * @param ForwardFW\Controller\Application $application The running application
     *
     * @return ForwardFW\Templater
     */
    final private static function createTemplater(
        Controller\Application $application
    ) {
        if (isset($GLOBALS['ForwardFW\\Templater'])) {
            $strTemplaterName = $application->getRequest()->getConfigParameter(
                'Templater',
                get_class()
            );
            include_once str_replace('\\', '/', $strTemplaterName) . '.php';
            $templater = new $strTemplaterName($application);
        } else {
            $application->getResponse()->addError('No Templater');
        }
        return $templater;
    }
}
