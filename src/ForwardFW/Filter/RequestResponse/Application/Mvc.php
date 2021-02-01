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

namespace ForwardFW\Filter\RequestResponse\Application;

/**
 * This class loads and runs a MVC Application.
 */
class Mvc extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Start Mvc Application chaining');
        $filters = $this->config->getFiltersController();
        $this->runFilters($filters);
        $this->response->addLog('Stop Mvc Application chaining');
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Start Mvc View chaining');
        $filters = $this->config->getFiltersView();
        $this->runFilters($filters);
        $this->response->addLog('Stop Mvc View chaining');
    }

    /**
     * Runs the filters given by config
     *
     * @param ForwardFW\Config\Filter[] Configuration of the filters.
     * @return void
     */
    protected function runFilters(array $filtersConfig)
    {
        if ($filtersConfig) {
            $filter = null;

            foreach (array_reverse($filtersConfig) as $filterConfig) {
                $filterClass = $filterConfig->getExecutionClassName();
                $filter = new $filterClass($filter, $filterConfig, $this->request, $this->response, $this->serviceManager);
            }

            try {
                $filter->doFilter();
            } catch (\Exception $e) {
                $this->response->addError($e->getMessage());
            }
        }
    }
}
