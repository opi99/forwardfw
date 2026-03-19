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

        if (isset($serverParams['PHP_AUTH_USER']) && isset($serverParams['PHP_AUTH_PW'])) {
            $hash = $this->config->getPassword();
            if ($serverParams['PHP_AUTH_USER'] === $this->config->getUsername() && password_verify($serverParams['PHP_AUTH_PW'], $hash)) {
                return AuthResult::grant();
            }
            return AuthResult::deny(AuthReason::INVALID_CREDENTIALS);
        } else {
            return AuthResult::abstain(AuthReason::BASIC_AUTH_REQUIRED);
        }
    }

    public function getLoginResponse(): ?ResponseInterface
    {
        $factory = new ResponseFactory();
        return $factory->createResponse(401, 'Authentication failed')
                ->withHeader('WWW-Authenticate', 'Basic realm="My Realm"');
    }

    public function getLogoutResponse(): ?ResponseInterface
    {
        return null;
    }
}
