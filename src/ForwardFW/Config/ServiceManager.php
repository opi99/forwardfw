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

namespace ForwardFW\Config;

/**
 * Config for a Service.
 */
class ServiceManager extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var string $executionClassName
     */
    protected $executionClassName = 'ForwardFW\\ServiceManager';
}
