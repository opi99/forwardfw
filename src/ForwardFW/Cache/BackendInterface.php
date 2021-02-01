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
interface BackendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend $config Configuration of Backend.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    );

    /**
     * Gets data from Cache.
     *
     * @param string $hash Hash for data.
     * @param integer $nTime Oldest Time of data in cache.
     *
     * @return mixed Data from cache
     */
    public function getData($hash, $nTime);

    /**
     * Sets data from Cache.
     *
     * @param string $hash Hash for data.
     * @param mixed $data Data to save into cache.
     *
     * @return void
     */
    public function setData($hash, $data);

    /**
     * Clears data from Cache.
     *
     * @param string $hash Hash for data.
     *
     * @return void
     */
    public function unsetData($hash);

    /**
     * Sets marker that cache will be generated yet.
     *
     * @param string $hash Hash of cache which is generated.
     *
     * @return void
     */
    public function setGenerating($hash);
}
