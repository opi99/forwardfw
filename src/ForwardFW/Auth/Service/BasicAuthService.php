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
class BasicAuthService
    extends \ForwardFW\Service\AbstractService
    implements AuthServiceInterface
{
    public function authenticate(ServerRequestInterface $request): AuthResult
    {
        $serverParams = $request->getServerParams();

        $decision = AuthDecision::DENIED;
        $reason = AuthReason::SYSTEM_FAILURE;

        if (isset($serverParams['PHP_AUTH_USER']) && isset($serverParams['PHP_AUTH_PW'])) {
            if ($serverParams['PHP_AUTH_USER'] === $this->config->getUsername() && $serverParams['PHP_AUTH_PW'] === $this->config->getPassword()) {
                $decision = AuthDecision::GRANT;
                $reason = AuthReason::NONE;
            }
            $reason = AuthReason::INVALID_CREDENTIALS;
        } else {
            $decision = AuthDecision::ABSTAIN;
            $reason = AuthReason::BASIC_AUTH_REQUIRED;
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
        $factory = new ResponseFactory();
        $response = $factory->createResponse(401, 'Authentication failed');
        $response = $response->withHeader('WWW-Authenticate', 'Basic realm="My Realm"');
        return $response;
    }

    public function getLogoutResponse(): ?ResponseInterface
    {
        return null;
    }
}
