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

namespace ForwardFW\Config\Middleware\SimpleRouter;

/**
 * Config for a SimpleRouter Filter.
 */
class Route extends \ForwardFW\Config
    implements \ForwardFW\Config\Middleware\MiddlewareIteratorInterface
{
    use \ForwardFW\Config\Traits\Middleware;

    /** @var string Startpoint of the route */
    private string $start;

    /** @var bool $loginRequired Defines, if a login is required for this route */
    private bool $loginRequired = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middlewares = new \ArrayObject();
    }

    /**
     * Sets Startpoint of the route
     *
     * @param string $strStart Startpoint of the route
     */
    public function setStart(string $start): self
    {
        $this->start = $start;
        return $this;
    }

    /**
     * Sets requirement of a login
     */
    public function loginRequired(): self
    {
        $this->loginRequired = true;
        return $this;
    }

    /**
     * Get Startpoint of the route.
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * Check if login is required for route
     */
    public function isLoginRequired(): bool
    {
        return $this->loginRequired;
    }
}
