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

namespace ForwardFW\Config\Templater;

use ForwardFW\Config\Traits;

/**
 * Config for a Application Filter.
 */
abstract class AbstractTemplater extends \ForwardFW\Config
{
    use Traits\Execution;

    /**
     * @var string Path to save the compiled versions of templates.
     */
    private $compilePath = '';

    /**
     * @var string Path to the templates
     */
    private $templatePaths = [];

    public function setCompilePath(string $compilePath): self
    {
        $this->compilePath = $compilePath;
        return $this;
    }

    public function addTemplatePath(string $templatePath): self
    {
        $this->templatePaths[] = $templatePath;
        return $this;
    }

    public function getCompilePath(): string
    {
        return $this->compilePath;
    }

    public function getTemplatePaths(): array
    {
        return $this->templatePaths;
    }
}
