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

namespace ForwardFW\Config\Runner;

/**
 * Config for the Runner.
 */
class HttpMiddlewareRunner extends \ForwardFW\Config\Runner
    implements \ForwardFW\Config\Middleware\MiddlewareIteratorInterface
{
    use \ForwardFW\Config\Traits\Middleware;

    /** @var string Class Name of executor */
    protected $executionClassName = \ForwardFW\Runner\HttpMiddlewareRunner::class;

    /**
     * @var boolean True if runner should send data otherwise false
     */
    private $shouldSend = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->middlewares = new \ArrayObject();
    }

    public function setShouldSend(bool $shouldSend): self
    {
        $this->shouldSend = $shouldSend;
        return $this;
    }


    public function getShouldSend(): bool
    {
        return $this->shouldSend;
    }
}
