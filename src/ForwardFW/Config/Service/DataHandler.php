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

namespace ForwardFW\Config\Service;

/**
 * Config for a Service.
 */
class DataHandler extends \ForwardFW\Config\Service
{
    protected string $executionClassName = \ForwardFW\Service\DataHandler::class;
    protected string $interfaceName = \ForwardFW\Service\DataHandlerInterface::class;

    // No different DataHandler configurable yet. Maybe remove DataHandler and declare this as different startable services?
    /** @var string prefix in tables. */
    private string $tablePrefix = '';

    private string $dsn = '';

    /**
     * Sets dsn for this connection.
     *
     * @param string $dsn DSN for database.
     */
    public function setDsn(string $dsn): self
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * Gets dsn for this connection.
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }

    /**
     * Sets prefix for the used tables.
     *
     * @param string $tablePrefix Prefix for Tables.
     */
    public function setTablePrefix(string $tablePrefix): self
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * Gets prefix for the used tables.
     */
    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }
}
