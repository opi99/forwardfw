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
        if (is_string($uri)) {
            $uriFactory = new UriFactory();
            $uri = $uriFactory->createUri($uri);
        }

        if (!$uri instanceof UriInterface) {
            throw new \InvalidArgumentException();
        }

        return new ServerRequest($method, $uri, [], $serverParams);
    }

    public static function createFromGlobals(): ServerRequestInterface
    {
        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri(
            (($_SERVER['HTTPS'] ?? null) === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/')
        );

        $headers = static::prepareHeaders($_SERVER);

        $request = new ServerRequest(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $uri,
            'php://input',
            $headers,
            $_COOKIE,
            $_SERVER
        );
        return $request;
    }


    /**
     * Fetch headers from $_SERVER variables
     * which are only the ones starting with HTTP_* and CONTENT_*
     *
     * @return array
     */
    protected static function prepareHeaders(array $server)
    {
        $headers = [];
        foreach ($server as $key => $value) {
            if (!is_string($key)) {
                continue;
            }
            if (str_starts_with($key, 'HTTP_COOKIE')) {
                // Cookies are handled using the $_COOKIE superglobal
                continue;
            }
            if (!empty($value)) {
                if (str_starts_with($key, 'HTTP_')) {
                    $name = str_replace('_', ' ', substr($key, 5));
                    $name = str_replace(' ', '-', ucwords(strtolower($name)));
                    $name = strtolower($name);
                    $headers[$name][] = $value;
                } elseif (str_starts_with($key, 'CONTENT_')) {
                    $name = substr($key, 8); // Content-
                    $name = 'Content-' . (($name === 'MD5') ? $name : ucfirst(strtolower($name)));
                    $name = strtolower($name);
                    $headers[$name][] = $value;
                }
            }
        }
        return $headers;
    }
}
