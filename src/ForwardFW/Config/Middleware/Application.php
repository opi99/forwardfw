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

namespace ForwardFW\Config\Middleware;

/**
 * Config for a Application Filter.
 */
class Application extends \ForwardFW\Config\Middleware
{
    /**
     * @var string Class of application to call
     */
    protected $executionClassName = \ForwardFW\Middleware\Application::class;

    /**
     * @var ForwardFW\Config\Application Config of the application
     */
    private $config = '';

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Application $config Config of the RequestResponse filter.
     */
    public function setConfig(\ForwardFW\Config\Application $config): self
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
