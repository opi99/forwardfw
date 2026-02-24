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

namespace ForwardFW\Config\Service;

/**
 * Config for a Event Dispatching Service.
 */
class SessionService extends \ForwardFW\Config\Service
{
    protected string $executionClassName = \ForwardFW\Service\SessionService::class;
    protected string $interfaceName = \ForwardFW\Service\SessionServiceInterface::class;
}
