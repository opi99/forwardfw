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
    protected string $executionClassName = \ForwardFW\Controller\Application::class;

    /**
     * @var string Name of the application
     */
    private string $name = '';

    /**
     * @var array Screens of the application
     */
    private array $screens = [];

    /**
     * @var string Identity of the application for get/post parameters
     */
    private string $ident = '';

    /**
     * Default content type of this application
     */
    private $contentType = 'text/html; charset=UTF-8';

    /**
     * Sets name of the application.
     *
     * @param string $name Name of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets screens of the application
     *
     * @param array $screens Screens of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setScreens(array $screens): self
    {
        $this->screens = $screens;
        return $this;
    }

    /**
     * Sets the ident of the application.
     *
     * @param string $ident Identity of the application for post/get parameters.
     *
     * @return ForwardFW\Config\Application
     */
    public function setIdent(string $ident): self
    {
        $this->ident = $ident;
        return $this;
    }

    /**
     * Get name of the application.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get screens of the application
     */
    public function getScreens(): array
    {
        return $this->screens;
    }

    /**
     * Get ident of the application.
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }
}
