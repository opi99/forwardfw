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

namespace ForwardFW\Service;

use ForwardFW\DataHandling\EntityMetadata;
use ForwardFW\DataHandling\Restriction\RestrictionInterface;

/**
 * Holds the active data restrictions
 */
class RestrictionService
    extends AbstractService
    implements RestrictionServiceInterface
{
    protected array $restrictions = [];

    public function __construct(\ForwardFW\Config\Service\RestrictionServiceConfig $config, \ForwardFW\ServiceManager $serviceManager)
    {
        parent::__construct($config, $serviceManager);

        $restrictions = $this->config->getRestrictions();

        foreach ($restrictions as $restriction) {
            $this->addRestriction($restriction);
        }

    }

    public function addRestriction(RestrictionInterface $restriction): void
    {
        $restrictionClassName = get_class($restriction);

        if (!isset($this->restrictions[$restrictionClassName])) {
            $this->restrictions[$restrictionClassName] = $restriction;
        }
    }

    public function removeRestriction(string $restrictionClassName): void
    {
        if (isset($this->restrictions[$restrictionClassName])) {
            unset($this->restrictions[$restrictionClassName]);
        }
    }

    public function hasRestriction(string $restrictionClassName): bool
    {
        return isset($this->restrictions[$restrictionClassName]);
    }

    /**
     * @return RestrictionInterface[]
     */
    public function getRestrictions(): array
    {
        return [];
    }

    public function applyRestrictions(array &$dataHandlerOptions, EntityMetadata $entityMetadata): void
    {
        foreach ($this->restrictions as $restriction) {
            $restriction->apply($dataHandlerOptions, $entityMetadata);
        }
    }
}
