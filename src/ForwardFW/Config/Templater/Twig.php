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

/**
 * Config for a Application Filter.
 */
class Twig extends AbstractTemplater
{
    /**
     * @var string TemplaterClass to call
     */
    protected string $executionClassName = \ForwardFW\Templater\Twig::class;

    protected array $extensionClasses = [];

    public function addExtensionClass(string $className): self
    {
        $this->extensionClasses[] = $className;
        return $this;
    }

    public function getExtensionClasses(): array
    {
        return $this->extensionClasses;
    }
}
