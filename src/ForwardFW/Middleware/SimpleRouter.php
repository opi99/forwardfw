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

        var_dump($request->getRequestTarget());

        foreach ($this->config->getRoutes() as $routeConfig) {
            if (strncmp($this->routePath, $routeConfig->getStart(), strlen($routeConfig->getStart())) === 0) {
                $nextRoute = substr($this->routePath, strlen($routeConfig->getStart()));
                if ($nextRoute === false) {
                    $nextRoute = '';
                }
                $subRequest = $request->withRequestTarget($nextRoute);

                $request = $this->setHandler($request, $route[1]);

                break;
            }
        }

        $logger->info('End Route');
        $response = $handler->handle($request);

        return $response;
    }

    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $parent = $this;

        $this->routePath = $this->request->getRoutePath();

        foreach ($this->config->getRoutes() as $routeConfig) {
            if (strncmp($this->routePath, $routeConfig->getStart(), strlen($routeConfig->getStart())) === 0) {
                $nextRoute = substr($this->routePath, strlen($routeConfig->getStart()));
                if ($nextRoute === false) {
                    $nextRoute = '';
                }
                $this->request->setRoutePath($nextRoute);

                $filterConfigs = $routeConfig->getFilterConfigs();
                foreach ($filterConfigs as $filterConfig) {
                    $filterClassName = $filterConfig->getExecutionClassName();
                    $child = new $filterClassName(null, $filterConfig, $this->request, $this->response, $this->serviceManager);
                    $parent->setChild($child);
                    $parent = $child;
                }
                break;
            }
        }
        if ($this->child === null && $this->config->getRouteNotFoundError()) {
            $this->response->addError('No Route "' . $this->routePath . '" found', 404);
        }
    }
}
