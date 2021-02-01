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

namespace ForwardFW\Config\Cache;

/**
 * Config for a Cache.
 */
class Frontend extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var ForwardFW\Config\Cache\Backend Configuration for Cache Backend
     */
    private $backendConfig = null;

    /**
     * Config of the Backend
     *
     * @param ForwardFW\Config\Cache\Backend $backendConfig Config of the backend cache.
     *
     * @return ForwardFW\Config\Cache\Frontend This.
     */
    public function setBackendConfig(\ForwardFW\Config\Cache\Backend $backendConfig)
    {
        $this->backendConfig = $backendConfig;
        return $this;
    }

    /**
     * Get config of cache backend.
     *
     * @return ForwardFW\Config\Cache\Backend Config of backend cache
     */
    public function getBackendConfig()
    {
        return $this->backendConfig;
    }
}
