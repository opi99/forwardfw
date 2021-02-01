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

namespace ForwardFW\Config\Filter\RequestResponse\SimpleRouter;

/**
 * Config for a SimpleRouter Filter.
 */
class Route extends \ForwardFW\Config
{
    /**
     * @var string Startpoint of the route
     */
    private $start = '';

    /**
     * @var ForwardFW\Config\Filter\RequestResponse[] Config of the filter
     */
    private $filterConfigs = array();

    /**
     * Sets Startpoint of the route
     *
     * @param string $strStart Startpoint of the route
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter
     */
    public function setStart($strStart)
    {
        $this->strStart = $strStart;
        return $this;
    }

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $filterConfig Config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse\SimpleRouter
     */
    public function addFilter(\ForwardFW\Config\Filter\RequestResponse $filterConfig)
    {
        $this->filterConfigs[] = $filterConfig;
        return $this;
    }

    /**
     * Get Startpoint of the route.
     *
     * @return string
     */
    public function getStart()
    {
        return $this->strStart;
    }

    /**
     * Get config of the RequestResponse filter.
     *
     * @return ForwardFW\Config\Filter\RequestResponse[]
     */
    public function getFilterConfigs()
    {
        return $this->filterConfigs;
    }
}
