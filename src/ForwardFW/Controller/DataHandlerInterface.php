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

namespace ForwardFW\Controller;

/**
 * Interface for DataHandlers
 */
interface DataHandlerInterface
{
    /**
     * Returns an instance of configured DataHandler.
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
//     public static function getInstance(ApplicationInterface $application);

    /**
     * Loads Data from cache or from a connection (DB, SOAP, File) if cache failed.
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFromCached($connectionName, array $options, $nCacheTimeout = -1);

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($connectionName, array $options);

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     * @param ForwardFW\Callback $idCallback Callback to give id of object creation
     *
     * @return mixed Data from the connection.
     */
    public function create($connectionName, array $options, \ForwardFW\Callback $idCallback = null);

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($connectionName, array $options);

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return void
     */
    public function initConnection($connectionName);

    /**
     * Quotes and escapes a string to be save in SQL query.
     *
     * @param string $connectionName Name of connection
     * @param string $value The string to be quoted savely
     *
     * @return string
     */
    public function quoteString($connectionName, $value);
}
