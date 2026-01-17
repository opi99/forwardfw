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

namespace ForwardFW\DataHandling;

use ForwardFW\Config\DataHandling\EntityMetadataFactoryAbstract as EntityMetadataFactoryAbstractConfig;
use ForwardFW\Service\AbstractService;
use ForwardFW\ServiceManager;

/**
 * Abstract Factory for EntityMetadata
 */
abstract class EntityMetadataFactoryAbstract
    extends AbstractService
    implements EntityMetadataFactoryInterface
{
    private array $entityMetadatas = [];

    public function __construct(EntityMetadataFactoryAbstractConfig $config, ServiceManager $serviceManager)
    {
        parent::__construct($config, $serviceManager);
    }

    public function get(string $entityName): EntityMetadata
    {
        if (!isset($this->entityMetadatas[$entityName])) {
            $this->entityMetadatas[$entityName] = $this->buildMetadataFor($entityName);
        }
        return $this->entityMetadatas[$entityName];
    }

    abstract protected function buildMetadataFor(string $entityName): EntityMetadata;
}
