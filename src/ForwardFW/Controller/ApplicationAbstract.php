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

namespace ForwardFW\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This Controller over one application.
 */
abstract class ApplicationAbstract extends View implements ApplicationInterface
{
    /** @var RequestInterface The request object */
    protected RequestInterface $request;

    /** @var \ForwardFW\ServiceManager The ServiceManager object */
    protected \ForwardFW\ServiceManager $serviceManager;

    /** @var \ForwardFW\Config\Application Configuration */
    protected \ForwardFW\Config\Application $config;

    public function __construct(
        \ForwardFW\Config\Application $config,
        RequestInterface $request,
        \ForwardFW\ServiceManager $serviceManager
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->serviceManager = $serviceManager;

        parent::__construct($this);
    }

    /**
     * Run screen and return generated content
     *
     * @return void
     */
    abstract public function run(): ResponseInterface;

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * Returns the ident of the application
     *
     * @return string
     */
    public function getIdent()
    {
        return $this->config->getIdent();
    }

    /**
     * Returns the request object
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Returns the response object of this process
     *
     * @return \ForwardFW\Response
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
