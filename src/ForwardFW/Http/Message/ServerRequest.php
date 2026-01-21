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

namespace ForwardFW\Http\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest
    extends Request
    implements ServerRequestInterface
{
    public function __construct(
        string $method,
        UriInterface $uri,
        $body = 'php://input',
        array $headers = [],
        protected array $cookieParams = [],
        protected array $serverParams = []
    ) {
        parent::__construct($method, $uri, $body, $headers);
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookieParams)
    {
        $clone = clone $this;
        $clone->setCookieParams($cookieParams);
        return $clone;
    }


    // @TODO
    public function getQueryParams()
    {
        return [];
    }

    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getUploadedFiles()
    {
        return [];
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getParsedBody()
    {

    }

    public function withParsedBody($data)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getAttributes()
    {
        return [];
    }

    public function getAttribute($name, $default = null)
    {
        return $default;
    }

    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        return $clone;
    }

    public function withoutAttribute($name)
    {
        $clone = clone $this;
        return $clone;
    }
}
