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

namespace ForwardFW;

class Bootstrap
{
    /**
     * @var ForwardFW\Config\Runner
     */
    private $config;

    public function __construct()
    {
    }

    public function loadConfig(string $file): void
    {
        $this->config = require $file;

        if (!$this->config instanceof Config\Runner) {
            throw new \ForwardFW\Exception\BootstrapException('Config didn\'t return a runner configuration.');
        }
    }

    public function run(): void
    {
        $class = $this->config->getExecutionClassName();
        $instance = new $class($this->config);
        $instance->run();
    }
}
