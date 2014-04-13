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
 * @category   Controller
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

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
class Controller
{
    /**
     * The application object.
     *
     * @var ForwardFW\Controller\Application
     */
    protected $application;

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public function __construct(Controller\ApplicationInterface $application)
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
    public function getParameter($strParameterName)
    {
        return $this->application->getRequest()->getParameter(
            $strParameterName,
            get_class($this),
            $this->application->getIdent()
        );
    }

    /**
     * Returns configuration of the given parameter for this class.
     *
     * @param string $strParameterName Name of parameter.
     *
     * @return mixed
     */
    public function getConfigParameter($strParameterName)
    {
        return $this->application->getRequest()->getConfigParameter(
            $strParameterName,
            get_class($this),
            $this->application->getIdent()
        );
    }
}
