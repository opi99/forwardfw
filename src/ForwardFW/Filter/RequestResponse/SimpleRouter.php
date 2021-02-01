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

namespace ForwardFW\Filter\RequestResponse;

/**
 * This class loads and runs the requested Application.
 */
class SimpleRouter extends \ForwardFW\Filter\RequestResponse
{
    /*
     * @var string Saved routePath till this point
     */
    private $routePath = '';

    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Start Route');
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

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('End Route');
    }

    /**
     * Function to process filtering incoming/child/outgoing
     *
     * @return void
     */
    public function doFilter()
    {
        try {
            parent::doFilter();
        } finally {
            // Restore routePath
            $this->request->setRoutePath($this->routePath);
        }
    }
}
