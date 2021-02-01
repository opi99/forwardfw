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

namespace ForwardFW\Config\Traits;

use ForwardFW\Config\Templater as TemplaterConfig;

/**
 * Config for a Application Filter.
 */
trait Templater
{
    /**
     * @var ForwardFW\Config\Templater
     */
    private $templaterConfig;

    public function setTemplaterConfig(TemplaterConfig $templaterConfig): object
    {
        $this->templaterConfig = $templaterConfig;
        return $this;
    }

    /**
     * Get config of Templater
     */
    public function getTemplaterConfig(): TemplaterConfig
    {
        return $this->templaterConfig;
    }
}
