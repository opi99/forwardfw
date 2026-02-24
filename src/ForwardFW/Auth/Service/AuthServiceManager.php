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

namespace ForwardFW\Auth\Service;

use ForwardFW\Auth\AuthDecision;
use ForwardFW\Auth\AuthReason;
use ForwardFW\Auth\AuthResult;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Implements Auth Service as Manager with AuthServices as sub services.
 */
class AuthServiceManager
    extends \ForwardFW\Service\AbstractService
    implements AuthServiceInterface
{
    protected AuthServiceInterface $lastAuthService;

    public function authenticate(ServerRequestInterface $request): AuthResult
    {
        $bestResult = null;
        foreach ($this->config->getSubServicesConfig() as $authServiceConfig) {
            $authService = $this->serviceManager->getService($authServiceConfig->getExecutionClassName());
            $result = $authService->authenticate($request);

            $this->lastAuthService = $authService;

            if ($result->getDecision() === AuthDecision::DENIED) {
                return $result;
            }

            if (!$bestResult
                || $bestResult->getDecision() === AuthDecision::ABSTAIN
                || $result->isFreshLogin()
            ) {
                $bestResult = $result;
                continue;
            }

            if ($bestResult->getDecision() === AuthDecision::GRANT
                && $result->getDecision() === AuthDecision::GRANT
            ) {
                /** @TODO Check for Level? FA2 etc */
            }
        }

        return $bestResult ?? AuthResult::deny(AuthReason::SYSTEM_FAILURE);
    }

    public function getLoginResponse(): ?ResponseInterface
    {
        return $this->lastAuthService->getLoginResponse();
    }

    public function getLogoutResponse(): ?ResponseInterface
    {
        return $this->lastAuthService->getLogoutResponse();
    }
}
