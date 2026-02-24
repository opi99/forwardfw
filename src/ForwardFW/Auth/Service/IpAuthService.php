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
 * IP Configuration auth service.
 */
class IpAuthService
    extends \ForwardFW\Service\AbstractService
    implements AuthServiceInterface
{

    public function authenticate(ServerRequestInterface $request): AuthResult
    {
        $serverParams = $request->getServerParams();
        $remoteAddress = trim($serverParams['REMOTE_ADDR'] ?? '');
        /** @TODO If known reverseProxy then use $serverParams['HTTP_X_FORWARDED_FOR'] as $remoteAddr */

        $typeOfIp = $this->determineTypeofIp($remoteAddress);

        $result = false;
        $decision = AuthDecision::DENIED;
        $reason = AuthReason::SYSTEM_FAILURE;

        switch ($typeOfIp) {
            case 'ipv4':
                $decision = $this->validateIpv4($remoteAddress);
                break;
            case 'ipv6':
                $decision = $this->validateIpv6($remoteAddress);
                break;
            default:
                // No access
        }

        if ($decision === AuthDecision::DENIED) {
            $reason = AuthReason::IP_BLOCKED;
        } else {
            return AuthResult::abstain();
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

    protected function determineTypeofIp(string $ip): string
    {
        $typeOfIp = 'unknown';
        if ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $typeOfIp = 'ipv4';
        } elseif ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $typeOfIp = 'ipv6';
        }
        return $typeOfIp;
    }

    protected function validateIpv4(string $ip): AuthDecision
    {
        $blacklist = $this->config->getBlacklistIpv4();
        if ($this->isInRangeIpv4($ip, $blacklist)) {
            return AuthDecision::DENIED;
        }
        $whitelist = $this->config->getWhitelistIpv4();
        if ($this->isInRangeIpv4($ip, $whitelist)) {
            return AuthDecision::GRANTED;
        }
        return AuthDecision::ABSTAIN;
    }

    protected function validateIpv6(string $ip): AuthDecision
    {
        $blacklist = $this->config->getBlacklistIpv6();
        if ($this->isInRangeIpv6($ip, $blacklist)) {
            return AuthDecision::DENIED;
        }
        $whitelist = $this->config->getWhitelistIpv6();
        if ($this->isInRangeIpv6($ip, $whitelist)) {
            return AuthDecision::GRANTED;
        }
        return AuthDecision::ABSTAIN;
    }

    protected function isInRangeIpv4($ip, $listOfIps): bool
    {
        foreach ($listOfIps as $range) {
            // Only IP direct support, no range yet
            if (ip2long($ip) === ip2long($range)) {
                return true;
            }
        }
        return false;
    }

    protected function isInRangeIpv6($ip, $listOfIps): bool
    {
        foreach ($listOfIps as $range) {
            // Only IP direct support, no range yet
            if (ip2long($ip) === ip2long($range)) {
                return true;
            }
        }
        return false;
    }
}
