<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Filter\RequestResponse\Application;

/**
 * This class loads and runs a MVC Application.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
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
                $filterClass = $filterConfig->getExecutionClass();
                $filter = new $filterClass($filter, $filterConfig, $this->request, $this->response);
            }

            try {
                $filter->doFilter();
            } catch (\Exception $e) {
                $this->response->addError($e->getMessage());
            }
        }
    }
}
