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

namespace ForwardFW;

/**
 * This class holds basic functions for controllers.
 */
class Controller
{
    /**
     * The application object.
     *
     * @var ForwardFW\Controller\Application
     */
    protected $application;

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public function __construct(Controller\ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Returns content of the given parameter for this class.
     *
     * @param string $strParameterName Name of parameter.
     *
     * @return mixed
     */
    public function getParameter($strParameterName)
    {
        return $this->application->getRequest()->getParameter(
            $strParameterName,
            get_class($this),
            $this->application->getIdent()
        );
    }

    /**
     * Returns configuration of the given parameter for this class.
     *
     * @param string $strParameterName Name of parameter.
     *
     * @return mixed
     */
    public function getConfigParameter($strParameterName)
    {
        return $this->application->getRequest()->getConfigParameter(
            $strParameterName,
            get_class($this),
            $this->application->getIdent()
        );
    }
}
