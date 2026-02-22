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

namespace ForwardFW\Config\Auth\Service;

/**
 * Config for the auth ip service.
 */
class IpAuthService extends AbstractAuthService
{
    protected $blacklistIpv4 = [];
    protected $blacklistIpv6 = [];
    protected $whitelistIpv4 = [];
    protected $whitelistIpv6 = [];

    protected string $executionClassName = \ForwardFW\Auth\Service\IpAuthService::class;

    public function setBlacklistIpv4(array $blacklistIpv4): self
    {
        $this->blacklistIpv4 = $blacklistIpv4;
        return $this;
    }

    public function getBlacklistIpv4(): array
    {
        return $this->blacklistIpv4;
    }

    public function setBlacklistIpv6(array $blacklistIpv6): self
    {
        $this->blacklistIpv6 = $blacklistIpv6;
        return $this;
    }

    public function getBlacklistIpv6(): array
    {
        return $this->blacklistIpv6;
    }

    public function setWhitelistIpv4(array $whitelistIpv4): self
    {
        $this->whitelistIpv4 = $whitelistIpv4;
        return $this;
    }

    public function getWhitelistIpv4(): array
    {
        return $this->whitelistIpv4;
    }

    public function setWhitelistIpv6(array $whitelistIpv6): self
    {
        $this->whitelistIpv6 = $whitelistIpv6;
        return $this;
    }

    public function getWhitelistIpv6(): array
    {
        return $this->whitelistIpv6;
    }
}
