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

namespace ForwardFW\Filter\RequestResponse\Login;

/**
 * This class sends the log and error message queue to the client via FirePHP.
 */
class BasicAuth extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Enter Filter');
        if (!$_SESSION['login']) {
            if (isset($_SERVER['PHP_AUTH_USER'])) {
                if ($_SERVER['PHP_AUTH_USER'] === 'ao' && $_SERVER['PHP_AUTH_PW'] === 'ao') {
                    $_SESSION['login'] = true;
                }
            }
        }
        if (!$_SESSION['login']) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            $this->response->setHttpStatus('401');
            $this->doStopChain = true;
        }
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Leave Filter');
    }
}
