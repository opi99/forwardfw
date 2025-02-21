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

namespace ForwardFW\Middleware\Logger;

use Clockwork\Support\Vanilla\Clockwork;
use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class sends the log and error message queue to the client via FirePHP.
 */
class ClockworkLogger extends \ForwardFW\Middleware
{
    protected Clockwork $clockwork;

    public function __construct(
        \ForwardFW\Config $config,
        // We have no DI yet
        \ForwardFW\ServiceManager $serviceManager
    ) {
        parent::__construct($config, $serviceManager);
        $this->clockwork = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class)->getClockwork();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute('clockwork', $this->clockwork);

        $this->clockwork->event('Controller')->begin();

        if ($response = $this->handleApiRequest($request)) return $response;
        if ($response = $this->handleWebRequest($request)) return $response;

        $response = $handler->handle($request);

        return $this->clockwork->usePsrMessage($request, $response)->requestProcessed();
    }

    // Handle a Clockwork REST api request if routing is enabled
    protected function handleApiRequest(ServerRequestInterface $request)
    {
        $path = $this->clockwork->getConfig()['api'];

        if (! preg_match("#^{$path}.*#", $request->getUri()->getPath())) return;

        return $this->clockwork->usePsrMessage($request, $this->prepareResponse())->handleMetadata();
    }

    // Handle a Clockwork Web interface request if routing is enabled
    protected function handleWebRequest(ServerRequestInterface $request)
    {
        $path = is_string($this->clockwork->getConfig()['web']['enable']) ? $this->clockwork->getConfig()['web']['enable'] : '/clockwork';

        if (! preg_match("#^{$path}(/.*)?#", $request->getUri()->getPath())) return;

        return $this->clockwork->usePsrMessage($request, $this->prepareResponse())->returnWeb();
    }

    protected function prepareResponse()
    {
        $responseFactory = new ResponseFactory();
        return $responseFactory->createResponse();
    }

}
