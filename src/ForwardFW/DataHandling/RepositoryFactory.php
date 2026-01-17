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

/**
 *
 */
class RepositoryFactory
    implements RepositoryFactoryInterface
{
    private array $repositories = [];

    public function get(EntityManagerInterface $entityManager, string $entityName)
    {
        $realName = $entityManager->getMetadata($entityName)->getRealName();

        return $this->repositories[$realName] ??= $this->create($entityManager, $entityName);
    }

    private function create(EntityManagerInterface $entityManager, string $entityName): EntityRepositoryInterface
    {
        $metadata = $entityManager->getMetadata($entityName);
        $repositoryClassName = $metadata->getRepositoryClassName() ?: \ForwardFW\DataHandling\EntityRepository::class;

        return new $repositoryClassName($entityManager, $metadata);
    }
}
