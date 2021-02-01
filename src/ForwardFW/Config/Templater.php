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
 * Config for a Application Filter.
 */
abstract class Templater extends \ForwardFW\Config
{
    use Traits\Execution;

    /**
     * @var string Path to save the compiled versions of templates.
     */
    private $strCompilePath = '';

    /**
     * @var string Path to the templates
     */
    private $strTemplatePath = '';

    public function setCompilePath($strCompilePath)
    {
        $this->strCompilePath = $strCompilePath;
        return $this;
    }

    public function setTemplatePath($strTemplatePath)
    {
        $this->strTemplatePath = $strTemplatePath;
        return $this;
    }

    public function getCompilePath()
    {
        return $this->strCompilePath;
    }

    public function getTemplatePath()
    {
        return $this->strTemplatePath;
    }
}
