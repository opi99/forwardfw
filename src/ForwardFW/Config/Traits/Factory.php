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

/**
 * Trait to config factory function functionality
 */
trait Factory
{
    /** @var string Function to a factory */
    // protected string $factoryFunction = null;

    public function setFactoryFunction(string $factoryFunction): self
    {
        $this->factoryFunction = $factoryFunction;
        return $this;
    }

    public function getFactoryFunction(): ?string
    {
        return isset($this->factoryFunction) ? $this->factoryFunction : null;
    }
}

