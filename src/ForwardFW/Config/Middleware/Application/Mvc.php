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

namespace ForwardFW\Config\Middleware\Application;

/**
 * Config for a Application Filter.
 */
class Mvc extends \ForwardFW\Config\Middleware
{
    /**
     * @var string Class of application to call
     */
    protected $executionClassName = \ForwardFW\Middleware\Application\Mvc::class;

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
     */
    public function addFilterController(\ForwardFW\Config\Middleware $filterConfig): self
    {
        $this->filterConfigsController[] = $filterConfig;
        return $this;
    }

    /**
     * Config of the RequestResponse filter
     *
     * @param ForwardFW\Config\Filter\RequestResponse $filterConfig Config of the RequestResponse filter.
     */
    public function addFilterView(\ForwardFW\Config\Middleware $filterConfig): self
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
