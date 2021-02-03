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

    protected $strView = null;

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
     *
     * @return string result of View
     */
    public function process()
    {
        $this->application->getResponse()->addLog('Processing ' . get_class($this));
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
    public function controlInput()
    {
        return true;
    }


    /**
     * Do some processing with user Input.
     *
     * @return boolean True if processing was succesfully.
     */
    public function processInput()
    {
        return true;
    }

    /**
     * Loads Data for views and defines which views to use.
     * strView is used.
     *
     * @return boolean True if screen wants to be viewed. Necessary for MultiApps.
     */
    public function controlView()
    {
        if ($this->strView) {
            $view = $this->loadView($this->strView);
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
                'VIEW_' . strtoupper(str_replace('\\', '_', $view->strViewName)),
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
    protected function addView(View $view)
    {
        $this->views->append($view);
        return $this;
    }

    /**
     * Loads the view by its Name.
     *
     * @param string $strViewClass Name of the View.
     *
     * @return ForwardFW\Controller\View The instance of the view.
     */
    protected function loadView($strViewClass)
    {
        if (class_exists($strViewClass)) {
            $view = new $strViewClass($this->application);
        } else {
            $this->application->getResponse()->addError('ViewClass "' . $strViewClass . '" not includeable.');
        }

        return $view;
    }

    /**
     * Returns the list of views to show.
     *
     * @return ArrayObject of ForwardFW\Controller\View The list of views.
     */
    public function getViews()
    {
        return $this->views;
    }
}
