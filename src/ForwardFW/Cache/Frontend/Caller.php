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

namespace ForwardFW\Cache\Frontend;

/**
 * Implementation of a Cache.
 */
class Caller extends \ForwardFW\Cache\Frontend
{
    /**
     * Returns content from cache or gathers the data
     *
     * @param ForwardFW\Config\Cache\Data $config What data should be get from cache
     *
     * @return mixed The data you requested.
     */
    public function getCache(\ForwardFW\Config\Cache\Data $config)
    {
        return parent::getCache($config);
    }

    /**
     * Returns hash for this config.
     *
     * @param ForwardFW\Config\Cache\Data $config For what the hash should calculated.
     *
     * @return string Hash for the config.
     */
    protected function calculateHash(\ForwardFW\Config\Cache\Data $config)
    {
        return $this->getHash($config->getCallback()->getParameters());
    }

    protected function getDataToCache(\ForwardFW\Config\Cache\Data $config)
    {
        return $config->getCallback()->doCallback();
    }
}
