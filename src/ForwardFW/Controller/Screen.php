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
 * This class is a basic Screen class.
 */
class Screen extends View implements ScreenInterface
{
    /**
     * The View which should be used.
     *
     * @var ArrayObject of ForwardFW\Controller\View
     */
    private $views;

    protected $viewClassName = \ForwardFW\Controller\View::class;

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     */
    public function __construct(ApplicationInterface $application)
    {
        parent::__construct($application);
        $this->views = new \ArrayObject();
    }

    /**
     * Processes the Screen.
     */
    public function process(): string
    {
        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->application->getServiceManager()->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('Processing Screen ' . get_class($this));

        $this->controlInput();
        $this->processInput();
        $this->controlView();
        return $this->processView();
    }

    /**
     * Control the user input, if available.
     *
     * @return boolean True if all user input was accepted.
     */
    public function controlInput(): bool
    {
        return true;
    }


    /**
     * Do some processing with user Input.
     *
     * @return boolean True if processing was succesfully.
     */
    public function processInput(): bool
    {
        return true;
    }

    /**
     * Loads Data for views and defines which views to use.
     *
     * @return boolean True if screen wants to be viewed. Necessary for MultiApps.
     */
    public function controlView(): bool
    {
        if ($this->viewClassName) {
            $view = $this->loadView($this->viewClassName);
            if (null === $view) {
                return false;
            }
            $this->addView($view);
        }

        return parent::controlView();
    }

    /**
     * Processes the View.
     */
    public function processView(): string
    {
        $templater = $this->application->getTemplater();

        foreach ($this->views as $view) {
            $templater->setVar(
                'VIEW_' . strtoupper(str_replace('\\', '_', $view->getViewName())),
                $view->process()
            );
        }
        return parent::processView();
    }

    /**
     * Adds a view to the list of views.
     *
     * @param ForwardFW\Controller\View $view The view to add.
     *
     * @return ForwardFW\Controller\Screen This Screen.
     */
    protected function addView(View $view): self
    {
        $this->views->append($view);
        return $this;
    }

    /**
     * Loads the view by its Name.
     *
     * @param string $viewClassName Name of the View.
     */
    protected function loadView($viewClassName):? \ForwardFW\Controller\View
    {
        $view = null;

        if (class_exists($viewClassName)) {
            $view = new $viewClassName($this->application);
        } else {
            /** @var \Psr\Log\LoggerInterface */
            $logger = $this->application->getServiceManager()->getService(\Psr\Log\LoggerInterface::class);
            $logger->error('ViewClass "' . $viewClassName . '" not includeable.');
        }

        return $view;
    }

    /**
     * Returns the list of views to show.
     *
     * @return ArrayObject of ForwardFW\Controller\View The list of views.
     */
    public function getViews(): \ArrayObject
    {
        return $this->views;
    }
}
