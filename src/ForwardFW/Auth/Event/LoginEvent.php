<?php

declare(strict_types=1);

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

namespace ForwardFW\Auth\Event;

use ForwardFW\Auth\AuthResult;
use ForwardFW\Event;

class LoginEvent
    extends Event
{
    public function __construct(
        protected AuthResult $authResult
    ) {
    }

    public function getAuthResult(): AuthResult
    {
        return $this->authResult;
    }
}
