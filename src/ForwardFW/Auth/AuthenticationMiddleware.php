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

namespace ForwardFW\Auth;

use ForwardFW\Auth\Service\AuthServiceInterface;
use ForwardFW\Auth\AuthDecision;
use ForwardFW\Auth\AuthResult;
use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class loads and runs the requested Application.
 */
class AuthenticationMiddleware extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Psr\Log\LoggerInterface */
        $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
        $logger->info('Start Authentication');

        /** @var ResponseInterface || null */
        $response = null;

        $authService = $this->serviceManager->getService(AuthServiceInterface::class);

        $authResult = $authService->authenticate($request);

        $request = $request->withAttribute(
            AuthResult::class,
            $authResult
        );

        if ($authResult->getDecision() === AuthDecision::DENIED && !$this->config->canProcessIfDenied()
            || $authResult->getDecision() === AuthDecision::ABSTAIN && !$this->config->canProcessIfAbstein()
        ) {
            $response = $this->buildResponse($authService, $authResult);
        }

        $logger->info('End Authentication ' . $authResult->getDecision()->value);

        if (!$response) {
            $response = $handler->handle($request);
        }

        return $response;
    }

    protected function buildResponse(AuthServiceInterface $authService, AuthResult $authResult): ResponseInterface
    {
        $response = $authService->getLoginResponse();

        if (!$response) {
            $factory = new ResponseFactory();
            $response = $factory->createResponse(403, $authResult->getReason());
        }
        return $response;
    }
}
