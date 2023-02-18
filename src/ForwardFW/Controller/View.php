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
 * This class is a basic View class.
 */
class View extends \ForwardFW\Controller
{
    /** @var string Name of the view, mostly class name */
    protected $viewName = '';

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\Application $application The running application.
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application)
    {
        parent::__construct($application);
        $this->viewName = get_class($this);
    }

    /**
     * Processes the View.
     *
     * @return string
     */
    public function process()
    {
        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->application->getServiceManager()->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('Processing View ' . get_class($this));

        $this->controlView();
        return $this->processView();
    }

    /**
     * Control available data for View
     *
     * @return boolean True if all data exists.
     */
    public function controlView()
    {
        return true;
    }

    /**
     * Processes the View.
     */
    public function processView(): string
    {
        $templater = $this->application->getTemplater();
        $templateFileName = $this->getTemplateName() . '.' . $templater->getTemplateFileEnding();

        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->application->getServiceManager()->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('Processing Template ' . get_class($this));

        $templater->setVar('ForwardFW_Version', \ForwardFW\Environment::VERSION);
        try {
            $templater->setTemplateFile($templateFileName);
            return $templater->getCompiled();
        } catch (\Exception $e) {
            $this->application->getResponse()->addError($e->getMessage());
        }
        return '';
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }

    /**
     * Returns the template name depending on the viewName
     * It replaces the underscore with path_slashes.
     *
     * @return string Name of the template
     */
    protected function getTemplateName()
    {
        // Replacing class slashes by path backslashes
        return strtr($this->viewName, '\\', '/');
    }
}
