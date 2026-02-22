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

use ForwardFW\Auth\AuthDecision;
use ForwardFW\Auth\AuthReason;

/*
 * Holds the result of the AuthenticationService
 */
final class AuthResult
{
    public function __construct(
        public readonly AuthDecision $decision,
        public readonly ?Identity $identity = null,
        public readonly int $level = 0,
        public readonly AuthReason $reason = AuthReason::NONE,
    ) {}

    public function getReason(): AuthReason
    {
        return $this->reason;
    }

    public function getDecision(): AuthDecision
    {
        return $this->decision;
    }

    public static function deny(string $reason): self
    {
        return new self(
            AuthDecision::DENIED,
            null,
            0,
            $reason
        );
    }
}
