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

namespace ForwardFW\Config\Middleware\Login;

/**
 * Config for a BasicAuth middleware.
 */
class BasicAuth extends \ForwardFW\Config\Middleware
{
    protected string $executionClassName = \ForwardFW\Middleware\Login\BasicAuth::class;

    protected string $username = '';

    protected string $password = '';

    /**
     * Username for BasicAuth
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Password for BasicAuth
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
