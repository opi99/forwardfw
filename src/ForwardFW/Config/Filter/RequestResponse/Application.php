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

namespace ForwardFW\Config\Filter\RequestResponse;

/**
 * Config for a Application Filter.
 */
class Application extends \ForwardFW\Config\Filter\RequestResponse
{
    /**
     * @var string Class of application to call
     */
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\Application';

    /**
     * @var ForwardFW\Config\Application Config of the application
     */
    private $config = '';

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Application $config Config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\Application
     */
    public function setConfig(\ForwardFW\Config\Application $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Application
     */
    public function getConfig()
    {
        return $this->config;
    }
}
