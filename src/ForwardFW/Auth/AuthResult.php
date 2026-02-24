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
        protected readonly AuthDecision $decision,
        protected readonly ?Identity $identity = null,
        protected readonly int $level = 0,
        protected readonly AuthReason $reason = AuthReason::NONE,
        protected readonly bool $freshLogin = false,
    ) {}

    public function getReason(): AuthReason
    {
        return $this->reason;
    }

    public function getDecision(): AuthDecision
    {
        return $this->decision;
    }

    public function isFreshLogin(): bool
    {
        return $this->freshLogin;
    }

    public static function deny(AuthReason $reason): self
    {
        return new self(
            AuthDecision::DENIED,
            null,
            0,
            $reason,
            false
        );
    }

    public static function abstain(AuthReason $reason = AuthReason::NOT_APPLICABLE): self
    {
        return new self(
            AuthDecision::ABSTAIN,
            null,
            0,
            $reason,
            false
        );

    }

    public static function grant(?Identity $identity = null, int $level = 0, bool $freshLogin = true): self
    {
        return new self(
            AuthDecision::GRANT,
            $identity,
            $level,
            AuthReason::NONE,
            true
        );
    }
}
