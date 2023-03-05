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

use ForwardFW\Http\Message\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\UriInterface;

class ServerRequestFactory
    implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, $serverParams);
    }

    public static function createFromGlobals(): ServerRequestInterface
    {
        $request = new ServerRequest(
            $_SERVER['REQUEST_METHOD'],
            (($_SERVER['HTTPS'] ?? null) === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/'),
            $_SERVER
        );
        if (!empty($_COOKIE)) {
            $request = $request->withCookieParams($_COOKIE);
        }
        return $request;
    }
}
