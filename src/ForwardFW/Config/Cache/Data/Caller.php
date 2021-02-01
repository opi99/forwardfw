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

namespace ForwardFW\Config\Cache\Data;

/**
 * Config for a Cache.
 */
class Caller extends \ForwardFW\Config\Cache\Data
{
    /**
     * @var ForwardFW_Callback The callback function object.
     */
    protected $callback = null;

    /**
     * Sets the callback
     *
     * @param ForwardFW\Callback $callback The callback configuration
     *
     * @return ForwardFW_Config_FunctionCacheData
     */
    public function setCallback(\ForwardFW\Callback $callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Gets the callback configuration
     *
     * @return ForwardFW\Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
