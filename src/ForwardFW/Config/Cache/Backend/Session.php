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
class Session extends \ForwardFW\Config\Cache\Backend
{
    protected $executionClassName = 'ForwardFW\\Cache\\Backend\\Session';

    /*
     * @var string Name of the session.
     */
    public $name;
}
