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
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Config\Filter\RequestResponse\Application;

/**
 * Config for a Application Filter.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Mvc extends \ForwardFW\Config\Filter\RequestResponse
{
    /**
     * @var string Class of application to call
     */
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\Application\\Mvc';

    /**
     * @var ForwardFW\Config\Filter\RequestResponse[] Config of the controller filter
     */
    private $filterConfigsController = array();

    /**
     * @var ForwardFW\Config\Filter\RequestResponse[] Config of the view filter
     */
    private $filterConfigsView = array();

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $filterConfig Config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\Application\Mvc
     */
    public function addFilterController(\ForwardFW\Config\Filter\RequestResponse $filterConfig)
    {
        $this->filterConfigsController[] = $filterConfig;
        return $this;
    }

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $filterConfig Config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\Application\Mvc
     */
    public function addFilterView(\ForwardFW\Config\Filter\RequestResponse $filterConfig)
    {
        $this->filterConfigsView[] = $filterConfig;
        return $this;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse[]
     */
    public function getFiltersController()
    {
        return $this->filterConfigsController;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse[]
     */
    public function getFiltersView()
    {
        return $this->filterConfigsView;
    }
}
