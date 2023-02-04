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

namespace ForwardFW\Http\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * This class represents the Request from browser.
 */
class Request
    extends Message
    implements RequestInterface
{
    /** @var string Path to route applications */
    protected $routePath = null;

    /** @var array data of json request */
    protected $json = null;

    /**
     * Returns the parameter for the application from browser request or
     * the session data.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $controllerClass Class name of the controller, wh asks.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The parameter for the application or null.
     */
    public function getParameter(
        string $parameterName,
        string $controllerClass = 'ForwardFW',
        string $applicationName = ''
    ) {
        $return = $this->getRequestParameter(
            $parameterName,
            $applicationName
        );
        if ($return === null) {
            $return = $this->getJsonParameter(
                $parameterName,
                $applicationName
            );
        }
        if ($return === null) {
            $return = $this->getConfigParameter(
                $parameterName,
                $controllerClass,
                $applicationName
            );
        }

        return $return;
    }

    /**
     * Returns the request parameter from browser/user session.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The parameter from the request.
     */
    public function getRequestParameter(
        string $parameterName,
        string $applicationName = ''
    ) {
        return $this->getParameterFromArray($_REQUEST, $parameterName, $applicationName);
    }

    /**
     * Gets the json request parameter
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The json parameter from the request.
     */
    public function getJsonParameter(
        string $parameterName,
        string $applicationName = ''
    ) {
        if ($this->json === null) {
            $this->json = json_decode(file_get_contents("php://input"), true);
            if ($this->json === null) {
                $this->json = [];
            }
        }

        return $this->getParameterFromArray($this->json, $parameterName, $applicationName);
    }

    /**
     * Gets the value of the parameter
     *
     * @param array $parameters The array containing the data to search in
     * @param string $parameterName Name of the parameter to return
     * @param string $applicationName Name of the application
     *
     * @return mixed The value of the parameter if set otherwise null
     */
    protected function getParameterFromArray(
        array $parameters,
        string $parameterName,
        string $applicationName = ''
    ) {
        $return = null;
        if (!empty($applicationName)) {
            if (isset($parameters[$applicationName])) {
                $requestData = $parameters[$applicationName];
            }
        } else {
            $requestData = $parameters;
        }
        if (isset($requestData[$parameterName])) {
            $return = $requestData[$parameterName];
        }
        return $return;
    }

    /**
     * Returns the config parameter for the application from configuration.
     *
     * @param string $parameterName   Name of the parameter to return.
     * @param string $controllerClass Class name of the controller, who asks.
     * @param string $applicationName Name of the application.
     *
     * @return mixed The configuration for the application or null.
     */
    public function getConfigParameter(
        string $parameterName,
        string $controllerClass = 'ForwardFW',
        string $applicationName = ''
    ) {
        $return = null;
        if (isset($GLOBALS[$parameterName])) {
            $return = $GLOBALS[$parameterName];
        }
        if (isset($GLOBALS['ForwardFW'][$parameterName])) {
            $return = $GLOBALS['ForwardFW'][$parameterName];
        }
        if (!empty($applicationName) && isset($GLOBALS[$applicationName][$parameterName])) {
            $return = $GLOBALS[$applicationName][$parameterName];
        }
        if (isset($GLOBALS[$controllerClass][$parameterName])) {
            $return = $GLOBALS[$controllerClass][$parameterName];
        }
        if (!empty($applicationName) && isset($GLOBALS[$applicationName][$controllerClass][$parameterName])) {
            $return = $GLOBALS[$applicationName][$controllerClass][$parameterName];
        }
        return $return;
    }

    /**
     * Returns the path for routing
     */
    public function getRoutePath(): string
    {
        if ($this->routePath === null) {
            $this->routePath = $this->findRoutePath();
        }

        return $this->routePath;
    }

    /**
     * Sets the path for routing
     *
     * @param string $routePath Path to route on
     */
    public function setRoutePath($routePath): void
    {
        $this->routePath = $routePath;
    }

    /**
     * Find the path to route out of the server request vars
     */
    protected function findRoutePath(): string
    {
        $hostPath = $this->getHostPath();
        if ($hostPath === '') {
            $routePath = $_SERVER['REQUEST_URI'];
        } else {
            $routePath = substr($_SERVER['REQUEST_URI'], strlen($hostPath));
        }

        return $routePath;
    }

    public function getHostPath(): string
    {
        $path = dirname($_SERVER['PHP_SELF']);
        if ($path === '/') {
            $path = '';
        }
        return $path;
    }

    /**
     * Name of the host (not set in CLI)
     */
    public function getHostName(): string
    {
        return $_SERVER['HTTP_HOST'];
    }



    // NEW

    public function getRequestTarget()
    {

    }

    public function withRequestTarget($requestTarget)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getMethod()
    {

    }

    public function withMethod($method)
    {
        $clone = clone $this;
        return $clone;
    }

    public function getUri()
    {

    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        return $clone;
    }
}
