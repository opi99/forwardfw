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

namespace ForwardFW\Middleware\Login;

use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class sends the log and error message queue to the client via FirePHP.
 */
class BasicAuth extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestParams = $request->getServerParams();
        $isLoggedIn = false;

        if (isset($requestParams['PHP_AUTH_USER']) && isset($requestParams['PHP_AUTH_PW'])) {
            if ($requestParams['PHP_AUTH_USER'] === 'ao' && $requestParams['PHP_AUTH_PW'] === 'ao') {
                $isLoggedIn = true;
            }
        }

        if ($isLoggedIn) {
            $response = $handler->handle($request);
        } else {
            $factory = new ResponseFactory();
            $response = $factory->createResponse(401, 'Authentication failed');
            $response = $response->withHeader('WWW-Authenticate', 'Basic realm="My Realm"');

            /** @var \Psr\Log\LoggerInterface */
            $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
            $logger->error('Authentication failed');
        }

        return $response;
    }
}
