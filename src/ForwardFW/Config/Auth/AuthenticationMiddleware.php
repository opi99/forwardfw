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

namespace ForwardFW\Config\Auth;

/**
 * Config for the auth middleware.
 */
class AuthenticationMiddleware extends \ForwardFW\Config\Middleware
{
    protected string $executionClassName = \ForwardFW\Auth\AuthenticationMiddleware::class;

    protected bool $processIfAbstein = false;

    protected bool $processIfDenied = false;

    public function processIfAbstein(bool $processIfAbstein = true): self
    {
        $this->processIfAbstein = $processIfAbstein;
        return $this;
    }

    public function canProcessIfAbstein(): bool
    {
        return $this->processIfAbstein;
    }

    public function processIfDenied(bool $processIfDenied = true): self
    {
        $this->processIfDenied = $processIfDenied;
        return $this;
    }

    public function canProcessIfDenied(): bool
    {
        return $this->processIfDenied;
    }
}
