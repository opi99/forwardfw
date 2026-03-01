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

namespace ForwardFW;

/**
 * This class can instantiate a templater class.
 */
class Templater
{
    /** @var \ForwardFW\Templater\TemplaterInterface */
    private static $instance = null;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Factory method to get Templater from config.
     *
     * @param ForwardFW\Controller\Application $application The running application
     *
     * @return ForwardFW\Templater
     */
    public static function factory(
        Config\Templater\AbstractTemplater $config,
        Controller\Application $application
    ): \ForwardFW\Templater\TemplaterInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = static::createTemplater($config, $application);
        }
        return self::$instance;
    }

    /**
     * Creation method of Templater from config.
     *
     * @param ForwardFW\Controller\Application $application The running application
     *
     * @return ForwardFW\Templater
     */
    private static function createTemplater(
        Config\Templater\AbstractTemplater $config,
        Controller\Application $application
    ): \ForwardFW\Templater\TemplaterInterface
    {
        $templaterClassName = $config->getExecutionClassName();
        $templater = new $templaterClassName($config, $application);
        return $templater;
    }
}
