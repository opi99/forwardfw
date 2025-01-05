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

namespace ForwardFW\Middleware\Application;

use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class loads and runs a MVC Application.
 */
class Mvc extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);

        $logger->info('Start Mvc Application chaining');
        $filters = $this->config->getFiltersController();
        $this->runFilters($filters, $request);
        $logger->info('Stop Mvc Application chaining');

        $logger->info('Start Mvc View chaining');
        $filters = $this->config->getFiltersView();
        $response = $this->runFilters($filters, $request);
        $logger->info('Stop Mvc View chaining');

        return $response;
    }

    /**
     * Runs the filters given by config
     *
     * @param ForwardFW\Config\Filter[] Configuration of the filters.
     */
    protected function runFilters(array $filtersConfig, ServerRequestInterface $request): ResponseInterface
    {
        $response = new ResponseFactory()->createResponse();

        if ($filtersConfig) {
            $filter = null;

            foreach (array_reverse($filtersConfig) as $filterConfig) {
                $filterClass = $filterConfig->getExecutionClassName();
                $filter = new $filterClass($filter, $filterConfig, $request, $response, $this->serviceManager);
            }

            try {
                $filter->doFilter();
            } catch (\Exception $e) {
                /** @var \Psr\Log\LoggerInterface */
                $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
                $logger->error($e->getMessage());
            }
        }

        return $response;
    }
}
