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
    protected array $configuredScreens = [];

    /** @var \ForwardFW\Controller\ScreenInterface Actual screen to process */
    protected ?\ForwardFW\Controller\ScreenInterface $processScreen = null;

    protected string $processScreenName = '';

    /** @var \ForwardFW\Templater\TemplaterInterface */
    protected ?\ForwardFW\Templater\TemplaterInterface $templater = null;

    public function __construct(
        \ForwardFW\Config\Application $config,
        RequestInterface $request,
        \ForwardFW\ServiceManager $serviceManager
    ) {
        parent::__construct($config, $request, $serviceManager);

        $this->configuredScreens = $this->config->getScreens();

        if (count($this->configuredScreens) === 0) {
            die(
                'No Screen defined for application: ' . $this->config->getName()
            );
        }
    }

    /**
     * Run screen and return generated content
     */
    public function run(): ResponseInterface
    {
        $content = '';
        $factory = new ResponseFactory();
        $response = $factory->createResponse();
        $response = $response->withHeader('Content-Type', $this->config->getContentType());

        try {
            $this->processScreenName = $this->getProcessScreen();
            $this->processScreen = $this->getScreenController(
                $this->processScreenName
            );
            if (!is_null($this->processScreen)) {
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
        $processScreen = $this->getParameter('screen');

        if ($processScreen === null) {
            $paths = preg_split('/\//', $this->application->getRequest()->getRequestTarget(), 2, PREG_SPLIT_NO_EMPTY);

            if (isset($paths[0])) {
                $processScreen = mb_ucfirst($paths[0]);
                if (isset($this->configuredScreens[$processScreen])) {
                    $this->request = $this->request->withRequestTarget($paths[1] ?? '');
                    return $processScreen;
                }
            }
        }

        if ($processScreen === null || !isset($this->configuredScreens[$processScreen])) {
            $processScreen = array_keys($this->configuredScreens)[0];
        }
        return $processScreen;
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
        $templater->setVar('SCREEN', $this->processScreen->process());
        return parent::processView();
    }

    public function getProcessScreenName(): string
    {
        return $this->processScreenName;
    }

    public function getTemplater(): \ForwardFW\Templater\TemplaterInterface
    {
        if (null === $this->templater) {
            $this->templater = \ForwardFW\Templater::factory($this->config->getTemplaterConfig(), $this);
        }
        return $this->templater;
    }
}
