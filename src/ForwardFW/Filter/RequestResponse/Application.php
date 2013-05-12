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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */


require_once 'ForwardFW/Filter/RequestResponse.php';

/**
 * This class loads and runs the requested Application.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Filter_RequestResponse_Application
    extends ForwardFW_Filter_RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Start Application');
        if (isset($GLOBALS['ForwardFW_Application'])) {
            $strApplicationClass = $GLOBALS['ForwardFW_Application']['class'];
            $strApplicationName  = $GLOBALS['ForwardFW_Application']['name'];
            include_once str_replace('_', '/', $strApplicationClass) . '.php';
            $application = new $strApplicationClass(
                $strApplicationName,
                $this->request,
                $this->response
            );
            $application->run();
        } else {
            $this->response->addError('No Application');
        }
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('End Application');
    }
}
