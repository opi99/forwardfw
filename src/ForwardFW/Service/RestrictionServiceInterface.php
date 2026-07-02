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

namespace ForwardFW\Service;

use ForwardFW\DataHandling\EntityMetadata;
use ForwardFW\DataHandling\Restriction\RestrictionInterface;

/**
 * Interface for RestrictionService
 */
interface RestrictionServiceInterface
{
    public function addRestriction(RestrictionInterface $restriction): void;

    public function removeRestriction(string $restrictionClassName): void;

    public function hasRestriction(string $restrictionClassName): bool;

    /**
     * @return RestrictionInterface[]
     */
    public function getRestrictions(): array;


    public function applyRestrictions(array &$dataHandlerOptions, EntityMetadata $entityMetadata): void;
}
