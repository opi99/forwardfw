<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW\Filter\RequestResponse;

require_once 'FirePHPCore/FirePHP.class.php';

/**
 * This class sends the log and error message queue to the client via FirePHP.
 */
class FirePhp extends \ForwardFW\Filter\RequestResponse
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
        $this->firephp = \FirePHP::getInstance(true);

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
    public function outputError()
    {
        $this->firephp->group('Response::Error');
        $arErrors = $this->response->getErrors()->getEntries();
        foreach ($arErrors as $strMessage) {
            $this->firephp->error($strMessage);
        }
        $this->firephp->groupEnd();
    }
}
