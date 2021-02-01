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

/**
 * This class loads and runs the requested Application.
 */
class Application extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $strClass = $this->config->getConfig()->getExecutionClassName();
        $this->response->addLog('Start Application: ' . $this->config->getConfig()->getName());

        $application = new $strClass(
            $this->config->getConfig(),
            $this->request,
            $this->response,
            $this->serviceManager
        );
        $application->run();
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
