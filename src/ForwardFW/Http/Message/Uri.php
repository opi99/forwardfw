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

use Psr\Http\Message\UriInterface;

class Uri
    implements UriInterface
{
    protected string $scheme = '';

    protected string $user = '';

    protected string $password = '';

    protected string $host = '';

    protected ?int $port = null;

    protected string $path = '';

    protected string $query = '';

    protected string $fragment = '';

    public function __construct(
        string $scheme = '',
        string $user = '',
        string $password = '',
        string $host = '',
        ?int $port = null,
        string $path = '',
        string $query = '',
        string $fragment = ''
    ) {
        $this->scheme = $scheme;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function withScheme(string $scheme): self
    {}

    public function getAuthority(): string
    {
        $userInfo = $this->getUserInfo();
        $authority = ($userInfo ? $userInfo . '@' : '');
        $authority .= $this->host;
        $authority .= ($this->port ? ':' . $this->port : '');

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->user . ($this->password ? ':' . $this->password : '');
    }

    public function withUserInfo(string $user, ?string $password = null): self
    {}

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {}

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function withPort(?int $port = null): self
    {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function withPath(string $path): self
    {}


    public function getQuery(): string
    {
        return $this->query;
    }

    public function withQuery(string $query): self
    {}

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withFragment(string $fragment): self
    {}

    public function __toString(): string
    {
        return '';
    }
}
