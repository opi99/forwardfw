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

use ForwardFW\Auth\AuthResult;
use ForwardFW\Service\AbstractService;
use ForwardFW\Service\SessionServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Load and verify session data auth service.
 */
class SessionAuthService
    extends AbstractService
    implements AuthServiceInterface
{
    public function authenticate(ServerRequestInterface $request): AuthResult
    {
        /** @var SessionServiceInterface */
        $session = $this->serviceManager->getService(SessionServiceInterface::class);
        /** @var AuthResult */
        $authResult = $session->get(AuthResult::class);

        if ($authResult) {
            /** @TODO Some verification here? */
            return new AuthResult(
                $authResult->getDecision(),
                null,
                0,
                $authResult->getReason()
            );
        }
        return AuthResult::abstain();
    }

    public function getLoginResponse(): ?ResponseInterface
    {
        return null;
    }

    public function getLogoutResponse(): ?ResponseInterface
    {
        return null;
    }
}

