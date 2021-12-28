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

declare(strict_types=1);

namespace ForwardFW;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest
    extends Request
    implements ServerRequestInterface
{
    public function getServerParams()
    {
        return $_SERVER;
    }

    public function getCookieParams()
    {
        return $_COOKIE;
    }

    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getQueryParams()
    {

    }

    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getUploadedFiles()
    {

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

    }

    public function getAttribute($name, $default = null)
    {

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

    public function getUri()
    {

    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        return $clone;
    }
}
