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

namespace ForwardFW\Config\Service\Logger;

/**
 * Config for a Service.
 */
class ClockworkLogger extends \ForwardFW\Config\Service\Logger
{
    protected string $executionClassName = \ForwardFW\Service\Logger\ClockworkLogger::class;

    protected bool $enable = false;

    public function enable(bool $enable = true): self
    {
        $this->enable = $enable;
        return $this;
    }

    public function getAsClockworkConfig(): array
    {
        return [
            'enable' => $this->enable,
        ];
    }
}

