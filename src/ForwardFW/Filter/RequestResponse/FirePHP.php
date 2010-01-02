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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

require_once 'ForwardFW/Filter/RequestResponse.php';

require_once 'FirePHPCore/FirePHP.class.php';

/**
 * This class sends the log and error message queue to the client via FirePHP.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Filter_RequestResponse_FirePHP
    extends ForwardFW_Filter_RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Enter Filter');
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Leave Filter');
        $this->firephp = FirePHP::getInstance(true);

        $this->outputLog();
        $this->outputError();
    }

    /**
     * Adds the response log entries to the log group in FirePHP
     *
     * @return void
     */
    private function outputLog()
    {
        $this->firephp->group('Response::Log');
        $arLogs = $this->response->getLogs()->getEntries();
        foreach ($arLogs as $strMessage) {
            $this->firephp->log($strMessage);
        }
        $this->firephp->groupEnd();
    }


    /**
     * Adds the response error entries to the error group in FirePHP
     *
     * @return void
     */
    function outputError()
    {
        $this->firephp->group('Response::Error');
        $arErrors = $this->response->getErrors()->getEntries();
        foreach ($arErrors as $strMessage) {
            $this->firephp->error($strMessage);
        }
        $this->firephp->groupEnd();
    }
}