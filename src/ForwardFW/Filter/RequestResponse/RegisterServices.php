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
class RegisterServices extends \ForwardFW\Filter\RequestResponse
{
    /*
     * @var string Saved routePath till this point
     */
    private $routePath = '';

    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Register Services');

        foreach ($this->config->getServices() as $serviceConfig) {
            $this->getServiceManager()->registerService($serviceConfig);
        }
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Stop Services');
        foreach ($this->config->getServices() as $serviceConfig) {
            $this->getServiceManager()->stopService($serviceConfig->getInterfaceName());
        }
    }
}
