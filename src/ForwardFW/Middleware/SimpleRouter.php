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

namespace ForwardFW\Middleware;

use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class loads and runs the requested Application.
 */
class SimpleRouter extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('Start Route');

        /** @var ResponseInterface || null */
        $response = null;

        $requestTargetPath = $request->getRequestTarget();

        foreach ($this->config->getRoutes() as $routeConfig) {
            if (strncmp($requestTargetPath, $routeConfig->getStart(), strlen($routeConfig->getStart())) === 0) {
                $nextRoute = substr($requestTargetPath, strlen($routeConfig->getStart()));
                if ($nextRoute === false) {
                    $nextRoute = '';
                }
                $subRequest = $request->withRequestTarget($nextRoute);

                $middlewareIterator = new MiddlewareIterator($routeConfig);
                $middlewareIterator->setServiceManager($this->serviceManager);
                $response = $middlewareIterator->handle($subRequest);

                break;
            }
        }

        $logger->info('End Route');

        if (!$response) {
            /** @TODO Own response with Error or proceed handling middlewares? */
            // $factory = new ResponseFactory();
            // §respons = $factory->createResponse
            // $response->addError('No Route "' . $routePath . '" found', 404);
            $response = $handler->handle($request);

        }

        return $response;
    }
}
