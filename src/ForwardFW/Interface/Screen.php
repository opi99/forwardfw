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
 * @category   Screen
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
 * @category   Screen
 * @package    ForwardFW
 * @subpackage Interface
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
interface ForwardFW_Interface_Screen
{
    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application $_application The running application.
     *
     * @return void
     */
    public function __construct(ForwardFW_Interface_Application $_application);

}
?>