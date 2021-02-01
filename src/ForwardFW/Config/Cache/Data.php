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
class Data extends \ForwardFW\Config
{
    /**
     * @var integer Timeout of the cache in seconds.
     */
    protected $nTimeout = 0;

    /**
     * @var boolean Should old data be returned if an error occours on fetching data.
     */
    protected $bReserveOld = false;

    /**
     * Sets the timeout
     *
     * @param integer Timeout in seconds.
     *
     * @return ForwardFW_Config_CacheData
     */
    public function setTimeout($nTimeout)
    {
        $this->nTimeout = $nTimeout;
        return $this;
    }

    /**
     * Sets the flag if Old data should be used on error while fetching data.
     *
     * @param boolean $bReserveOld If old data should be reserved.
     *
     * @return ForwardFW_Config
     */
    public function setReserveOld($bReserveOld)
    {
        $this->bReserveOld = $bReserveOld;
        return $this;
    }

    /**
     * Gets the timeout
     *
     * @return integer The timeout in seconds.
     */
    public function getTimeout()
    {
        return $this->nTimeout;
    }

    /**
     * Gets the flag for old data handling.
     *
     * @return boolean State of the flag.
     */
    public function getReserveOld()
    {
        return $this->bReserveOld;
    }
}
