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
 * This Interface must be implemented from an application.
 */
interface ApplicationInterface
{
    /**
     * Constructor
     *
     * @param \ForwardFW\Config\Application $config   Name of application.
     * @param \ForwardFW\Request            $request  The ForwardFW request object.
     * @param \ForwardFW\Response           $response The ForwardFW response object.
     * @param \ForwardFW\Service            $serviceManager The services for this application
     */
    public function __construct(
        \ForwardFW\Config\Application $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response,
        \ForwardFW\ServiceManager $serviceManager
    );

    /**
     * Run screen and return generated content
     *
     * @return string generated content form screens
     */
    public function run();

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the ident of the application
     *
     * @return string
     */
    public function getIdent();

    /**
     * Returns the request object
     *
     * @return \ForwardFW\Request
     */
    public function getRequest();

    /**
     * Returns the response object
     *
     * @return \ForwardFW\Response
     */
    public function getResponse();

    /**
     * Returns the response object of this process
     *
     * @return \ForwardFW\Response
     */
    public function getServiceManager();
}
