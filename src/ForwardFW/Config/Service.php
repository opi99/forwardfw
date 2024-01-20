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
class Service extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var string Interface which this service represents.
     */
    protected string $interfaceName = '';

    /**
     * Sets name of interface this service represents.
     *
     * @param string $interfaceName Name of interface this service represents.
     *
     * @return ForwardFW\Config\Service
     */
    public function setInterfaceName(string $interfaceName)
    {
        $this->interfaceName = $interfaceName;
        return $this;
    }

    /**
     * Get name of interface this service represents.
     */
    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }
}
