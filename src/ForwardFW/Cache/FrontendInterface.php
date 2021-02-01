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

namespace ForwardFW\Cache;

/**
 * Interface for a Cache.
 */
interface FrontendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Cache\BackendInterface $backend Backend for storing cache data.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Cache\BackendInterface $backend
    );

    /**
     * Builds an instance of cache
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     * @param ForwardFW\Config\Cache\Frontend $config Configuration of caching
     *
     * @return ForwardFW_Interface_Cache_Frontend The cache Frontend
     */
    public static function getInstance(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Frontend $config
    );

    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW\Config\Cache\Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(
        \ForwardFW\Config\Cache\Data $config
    );
}
