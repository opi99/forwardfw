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

namespace ForwardFW\Cache\Backend;

/**
 * Implementation of a Cache Backend.
 */
class Session extends \ForwardFW\Cache\Backend
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend $config Backend config.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    ) {
        parent::__construct($application, $config);

        session_name($this->config->getName());
        session_start();
    }

    /**
     * Writes data into the cache
     *
     *
     * @param string $hash Hash for data.
     * @param array $arData Data to save into cache.
     *
     * @return void
     */
    protected function writeData($hash, array $arData)
    {
        $_SESSION['cache'][$hash] = $arData;
    }

    /**
     * Reads data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return array Data from the storage
     */
    protected function readData($hash)
    {
        return $_SESSION['cache'][$hash];
    }

    /**
     * Removes data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return boolean Returns true if data removed otherwise false.
     */
    protected function removeData($hash)
    {
        unset($_SESSION['cache'][$hash]);
        return true;
    }

    /**
     * Clear complete cache
     *
     * @return void
     */
    protected function clear($hash)
    {
        $_SESSION['cache'] = array();
        return true;
    }
}
