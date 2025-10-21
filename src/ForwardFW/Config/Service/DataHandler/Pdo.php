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

namespace ForwardFW\Config\Service\DataHandler;

/**
 * Config for a Pdo DataHandler Service.
 */
class Pdo extends \ForwardFW\Config\Service\DataHandler
{
    protected string $executionClassName = \ForwardFW\Service\DataHandler\Pdo::class;

    private string $username;

    private string $password;

    /**
     * Sets username for this connection.
     *
     * @param string $username DSN for database.
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets username for this connection.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets password for this connection.
     *
     * @param string $password DSN for database.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Gets password for this connection.
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
