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

namespace ForwardFW\Service;

/**
 * Interface for DataHandlers
 */
interface DataHandlerInterface
{
    /**
     * Loads Data from cache or from a connection (DB, SOAP, File) if cache failed.
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     * @param int $cacheTimeout Timeout of caching, -1 for disable caching
     *
     * @return mixed Data from the connection.
     */
    public function loadFromCached(string $connectionName, array $options, int $cacheTimeout = -1);

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom(string $connectionName, array $options);

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return ?int Last insert id if requested
     */
    public function create(string $connectionName, array $options): ?int;

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo(string $connectionName, array $options);

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return void
     */
    public function initConnection(string $connectionName);

    /**
     * Quotes and escapes a string to be save in SQL query.
     *
     * @param string $connectionName Name of connection
     * @param string $value The string to be quoted savely
     *
     * @return string
     */
    public function quoteString(string $connectionName, $value);
}
