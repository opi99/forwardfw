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

namespace ForwardFW\Config;

/**
 * Config for a Application Filter.
 */
class Application extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;
    use \ForwardFW\Config\Traits\Templater;

    /**
     * @var string $executionClassName
     */
    protected $executionClassName = \ForwardFW\Controller\Application::class;

    /**
     * @var string Name of the application
     */
    private $strName = '';

    /**
     * @var array Screens of the application
     */
    private $arScreens = '';

    /**
     * @var string Identity of the application for get/post parameters
     */
    private $strIdent = '';

    /**
     * Default content type of this application
     */
    private $contentType = 'text/html; charset=UTF-8';

    /**
     * Sets name of the application.
     *
     * @param string $strName Name of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setName($strName)
    {
        $this->strName = $strName;
        return $this;
    }

    /**
     * Sets screens of the application
     *
     * @param array $arScreens Screens of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setScreens(array $arScreens)
    {
        $this->arScreens = $arScreens;
        return $this;
    }

    /**
     * Sets the ident of the application.
     *
     * @param string $strIdent Identity of the application for post/get parameters.
     *
     * @return ForwardFW\Config\Application
     */
    public function setIdent($strIdent)
    {
        $this->strIdent = $strIdent;
        return $this;
    }

    /**
     * Get name of the application.
     *
     * @return string
     */
    public function getName()
    {
        return $this->strName;
    }

    /**
     * Get screens of the application
     *
     * @return array
     */
    public function getScreens()
    {
        return $this->arScreens;
    }

    /**
     * Get ident of the application.
     *
     * @return string
     */
    public function getIdent()
    {
        return $this->strIdent;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
    }
}
