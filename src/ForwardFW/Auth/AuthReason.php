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

namespace ForwardFW\Auth;

enum AuthReason: string
{
    case NONE = 'none';

    // hard facts
    case INVALID_CREDENTIALS = 'invalid_credentials';
    case IP_BLOCKED = 'ip_blocked';
    case SYSTEM_FAILURE = 'system_failure';
    case RATE_LIMIT = 'rate_limit';
    case ACCOUNT_DISABLED = 'account_disabled';

    // Challenge
    case BASIC_AUTH_REQUIRED = 'basic_auth_required';

    // neutral
    case NOT_APPLICABLE = 'not_applicable';
}
