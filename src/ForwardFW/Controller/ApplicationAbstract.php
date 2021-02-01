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

namespace ForwardFW\Controller;

/**
 * This Controller over one application.
 */
abstract class ApplicationAbstract extends View implements ApplicationInterface
{
    /**
     * The request object.
     *
     * @var \ForwardFW\Request
     */
    protected $request;

    /**
     * The response object.
     *
     * @var \ForwardFW\Response
     */
    protected $response;

    /**
     * @var ForwardFW\ServiceManager The ServiceManager object
     */
    protected $serviceManager = null;

    /**
     * @var \ForwardFW\Config\Application Configuration
     */
    protected $config = null;

    /**
     * Constructor
     *
     * @param \ForwardFW\Config\Application $config         Name of application.
     * @param \ForwardFW\Request            $request        The request object.
     * @param \ForwardFW\Response           $response       The request object.
     * @param \ForwardFW\Service            $serviceManager The services for this application
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\Application $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response,
        \ForwardFW\ServiceManager $serviceManager
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
        $this->serviceManager = $serviceManager;

        parent::__construct($this);
    }

    /**
     * Run screen and return generated content
     *
     * @return void
     */
    abstract public function run();

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
     *
     * @return \ForwardFW\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response object
     *
     * @return \ForwardFW\Response
     */
    public function getResponse()
    {
        return $this->response;
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
