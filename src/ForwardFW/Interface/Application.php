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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.5
 */

/**
 *
 */

/**
 * This Interface must be implemented from an application.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface ForwardFW_Interface_Application
{
    /**
     * Constructor
     *
     * @param string             $_strApplicationName Name of application.
     * @param ForwardFW_Request  $_request            The ForwardFW request object.
     * @param ForwardFW_Response $_response           The ForwardFW response object.
     *
     * @return void
     */
    public function __construct(
        $_strApplicationName,
        ForwardFW_Request $_request,
        ForwardFW_Response $_response
    );

    /**
     * Run screen and return generated content
     *
     * @return string generated content form screens
     */
    public function run();


    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getApplicationName();
}
?>