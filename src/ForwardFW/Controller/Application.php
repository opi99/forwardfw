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

use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This Controller over one application.
 */
class Application extends ApplicationAbstract
{
    /** @var array screens for this application */
    protected $configuredScreens = [];

    /** @var \ForwardFW\Controller\ScreenInterface Actual screen to process */
    protected $screen = null;

    /** @var \ForwardFW\Templater\TemplaterInterface */
    protected $templater = null;

    public function __construct(
        \ForwardFW\Config\Application $config,
        RequestInterface $request,
        \ForwardFW\ServiceManager $serviceManager
    ) {
        parent::__construct($config, $request, $serviceManager);

        $this->configuredScreens = $this->config->getScreens();

        if (count($this->configuredScreens) === 0) {
            die(
                'No Screen defined for application: ' . $this->strName
            );
        }
    }

    /**
     * Run screen and return generated content
     */
    public function run(): ResponseInterface
    {
        $content = '';
        $strProcessScreen = $this->getProcessScreen();
        $factory = new ResponseFactory();
        $response = $factory->createResponse();
        $response = $response->withHeader('Content-Type', $this->config->getContentType());

        try {
            $this->screen = $this->getScreenController(
                $this->getProcessScreen()
            );
            if (!is_null($this->screen)) {
                // @TODO evaluate State of Screen
                $content = $this->processView();
            }
        } catch (\ForwardFW\Exception $e) {
            // Todo Inner Exception Logging
            throw $e;
        } catch (\Exception $e) {
            // Todo Logging
            throw $e;
        }

        $body = $response->getBody();
        $body->write($content);

        return $response;
    }

    /**
     * Returns name of screen to be processed
     *
     * @return string name of screen to process
     */
    public function getProcessScreen(): string
    {
        $strProcessScreen = $this->getParameter('screen');
        if (!isset($this->configuredScreens[$strProcessScreen])) {
            $strProcessScreen = array_keys($this->configuredScreens)[0];
        }
        return $strProcessScreen;
    }

    /**
     * Load and return screen with given name
     */
    public function getScreenController(string $screenName):? \ForwardFW\Controller\ScreenInterface
    {
        $screenController = null;
        $screenClassName = $this->configuredScreens[$screenName];

        if (class_exists($screenClassName)) {
            $screenController = new $screenClassName($this);
        } else {
            /** @var \Psr\Log\LoggerInterface */
            $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
            $logger->error('ScreenClass "' . $screenClassName . '" not includeable.');
        }

        return $screenController;
    }

    /**
     * Processes the View.
     */
    public function processView(): string
    {
        $templater = $this->getTemplater();
        $templater->setVar('APPLICATION', $this);
        $templater->setVar('SCREEN', $this->screen->process());
        return parent::processView();
    }

    /**
     * Returns the screen configuration for this application.
     */
    public function getScreens(): array
    {
        return $this->configuredScreens;
    }

    public function getTemplater(): \ForwardFW\Templater\TemplaterInterface
    {
        if (null === $this->templater) {
            $this->templater = \ForwardFW\Templater::factory($this->config->getTemplaterConfig(), $this);
        }
        return $this->templater;
    }
}
