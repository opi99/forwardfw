<?php

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

namespace ForwardFW\Config\Service;

use ForwardFW\DataHandling\Restriction\RestrictionInterface;

/**
 * Config for the Restriction Service.
 */
class RestrictionServiceConfig extends \ForwardFW\Config\Service
{
    protected string $executionClassName = \ForwardFW\Service\RestrictionService::class;
    protected string $interfaceName = \ForwardFW\Service\RestrictionServiceInterface::class;

    protected array $restrictions = [];

    public function addRestriction(RestrictionInterface $restriction): self
    {
        $this->restrictions[] = $restriction;
        return $this;
    }

    /**
     * @return RestrictionInterface[]
     */
    public function getRestrictions(): array
    {
        return $this->restrictions;
    }
}
