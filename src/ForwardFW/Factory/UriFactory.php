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

namespace ForwardFW\Factory;

use ForwardFW\Http\Message\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory
    implements UriFactoryInterface
{
    public function createUri(string $uri = '') : UriInterface
    {
        $parsedUrl = parse_url($uri);
        return new Uri(
            ($parsedUrl['scheme'] ?? ''),
            ($parsedUrl['user'] ?? ''),
            ($parsedUrl['pass'] ?? ''),
            ($parsedUrl['host'] ?? ''),
            ($parsedUrl['port'] ?? null),
            ($parsedUrl['path'] ?? ''),
            ($parsedUrl['query'] ?? ''),
            ($parsedUrl['fragment'] ?? ''),
        );
    }
}
