<?php

declare(strict_types=1);

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

namespace ForwardFW\Config\Middleware\Logger;

/**
 * Config for a Filter which outputs logging to ChromeLogger
 */
class ChromeLogger extends \ForwardFW\Config\Middleware
{
    /** @var string */
    protected $executionClassName = \ForwardFW\Middleware\Logger\ChromeLogger::class;
}
