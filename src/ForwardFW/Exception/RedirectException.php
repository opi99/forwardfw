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

namespace ForwardFW\Exception;

/**
 * Exception to get redirected
 */
class RedirectException extends \ForwardFW\Exception
{
    protected string $location;

    public function __construct(string $message, string $location, int $code = 0, ?Throwable $previous = null) {
        $this->location = $location;
        parent::__construct($message, $code, $previous);
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
