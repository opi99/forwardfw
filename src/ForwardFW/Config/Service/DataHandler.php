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
 * Config for a Service.
 */
class DataHandler extends \ForwardFW\Config\Service
{
    protected $executionClassName = 'ForwardFW\\Controller\\DataHandler';

    protected $interfaceName = 'ForwardFW\\Controller\\DataHandlerInterface';

    // No different DataHandler configurable yet. Maybe remove DataHandler and declare this as different startable services?
    /** @var string prefix in tables. */
    private $tablePrefix = '';

    private $dsn = '';

    /**
     * Sets dsn for this connection.
     *
     * @param string $dsn Prefix for Tables.
     *
     * @return \ForwardFW\Config\Service\DataHandler
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * Gets dsn for this connection.
     *
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * Sets prefix for the used tables.
     *
     * @param string $tablePrefix Prefix for Tables.
     *
     * @return \ForwardFW\Config\Service\DataHandler
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * Gets prefix for the used tables.
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }
}
