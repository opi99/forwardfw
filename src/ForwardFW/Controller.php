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
 * @category   Controller
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009,2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

/**
 *
 */
require_once 'ForwardFW/Interface/Application.php';

/**
 * This class holds basic functions for controllers.
 *
 * @category   Controller
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Controller
{
    /**
     * The application object.
     *
     * @var ForwardFW_Interface_Application
     */
    protected $application;

    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application $application The running application.
     *
     * @return void
     */
    public function __construct(ForwardFW_Interface_Application $application)
    {
        $this->application = $application;
    }

    /**
     * Returns content of the given parameter for this class.
     *
     * @param string $strParameterName Name of parameter.
     *
     * @return mixed
     */
    function getParameter($strParameterName)
    {
        return $this->application->getRequest()->getParameter(
            $strParameterName,
            get_class($this),
            $this->application->getName()
        );
    }

    /**
     * Returns configuration of the given parameter for this class.
     *
     * @param string $strParameterName Name of parameter.
     *
     * @return mixed
     */
    function getConfigParameter($strParameterName)
    {
        return $this->application->getRequest()->getConfigParameter(
            $strParameterName,
            get_class($this),
            $this->application->getName()
        );
    }
}