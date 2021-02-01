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

namespace ForwardFW\Config\Cache\Backend;

/**
 * Config for a Cache.
 */
class File extends \ForwardFW\Config\Cache\Backend
{
    protected $executionClassName = 'ForwardFW\\Cache\\Backend\\File';

    /*
     * @var string Path to cache
     */
    private $path;

    /**
     * Sets the path where this cache lies.
     *
     * @param string $path The path to cache data into.
     *
     * @return ForwardFW\Config\Cache\Backend\File This.
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Gets the path where this cache lies.
     *
     * @return string The path to cache data into.
     */
    public function getPath()
    {
        return $this->Path;
    }
}
