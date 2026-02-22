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
use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP Basic Auth auth service.
 */
class FormAuthService
    extends \ForwardFW\Service\AbstractService
    implements AuthServiceInterface
{
    public function authenticate(ServerRequestInterface $request): AuthResult
    {
        $serverParams = $request->getServerParams();

        $decision = AuthDecision::DENIED;
        $reason = AuthReason::SYSTEM_FAILURE;

        if ($request->getMethod() === 'POST'
            && $request->getUri()->getPath() === $this->config->getLoginPath()
        ) {
            $postParams = $request->getParsedBody();
            if (!is_array($postParams)) {
                return AuthResult::deny(AuthReason::INVALID_CREDENTIALS);
            }

            $username = $postParams['username'] ?? null;
            $password = $postParams['password'] ?? null;

            if (!$username || !$password) {
                return AuthResult::deny(AuthReason::INVALID_CREDENTIALS);
            }

            $reason = AuthReason::INVALID_CREDENTIALS;

            if ($username === $this->config->getUsername() && $password === $this->config->getPassword()) {
                $decision = AuthDecision::GRANT;
                $reason = AuthReason::NONE;
            }
        } else {
            $decision = AuthDecision::ABSTAIN;
            $reason = AuthReason::NOT_APPLICABLE;
        }

        return new AuthResult(
            $decision,
            null,
            0,
            $reason
        );
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

