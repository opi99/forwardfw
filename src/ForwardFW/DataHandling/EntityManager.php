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

use ForwardFW\Service\AbstractService;

/**
 * Manager for different entities
 */
class EntityManager
    extends AbstractService
    implements EntityManagerInterface
{
    private array $entitiesLoaded = [];

    private array $entitiesDataOriginal = [];

    private array $entitiesForUpdates = [];

    private ?EntityMetadataFactoryInterface $entityMetadataFactory = null;

    private ?RepositoryFactoryInterface $repositoryFactory = null;

    public function __construct(\ForwardFW\Config\DataHandling\EntityManager $config, \ForwardFW\ServiceManager $serviceManager)
    {
        parent::__construct($config, $serviceManager);
        $this->entityMetadataFactory = $serviceManager->getService(EntityMetadataFactoryInterface::class);
        $this->repositoryFactory = $serviceManager->getService(RepositoryFactoryInterface::class);
    }

    public function getMetadata(string $entityName): EntityMetadata
    {
        return $this->entityMetadataFactory->get($entityName);
    }

    public function getRepository(string $entityName): EntityRepositoryInterface
    {
        return $this->repositoryFactory->get($this, $entityName);
    }

    public function getEntityMapper(string $entityName): EntityMapperInterface
    {
        /** @TODO Factory? */
        return new EntityMapper($this, $this->getMetadata($entityName));
    }

    public function getEntityExtractor(string $entityName): EntityExtractorInterface
    {
        /** @TODO Factory? */
        return new EntityExtractor($this, $this->getMetadata($entityName));
    }

    /** @TODO Rename functions? Move into "entitiesContainer"? */
    public function has(string $entityName, int|string $identifier): bool
    {
        return isset($this->entitiesLoaded[$entityName][$identifier]);
    }

    public function get(string $entityName, int|string $identifier): object
    {
        return $this->entitiesLoaded[$entityName][$identifier];
    }

    public function load(string $entityName, int|string $identifier): object
    {
        return $this->getRepository($entityName)->findByIdentifier($identifier);
    }

    public function register(string $entityName, int|string $identifier, object $entity, array $dataOriginal): void
    {
        if ($this->has($entityName, $identifier)) {
            throw new \LogicException('Entity "' . $entityName . '" with identifier "' . $identifier . '" already registered');
        }
        $this->entitiesLoaded[$entityName][$identifier] = $entity;
        $this->entitiesDataOriginal[$entityName][$identifier] = $dataOriginal;
    }

    public function persist(object $entity): void
    {
        $class = get_class($entity);
        $idMethod = 'getId';
        if (!method_exists($entity, $idMethod)) {
            throw new \LogicException("Entity of class $class has no getId method");
        }

        $id = $entity->$idMethod();

        $this->entitiesForUpdates[$class][$id ?? spl_object_id($entity)] = $entity;
    }

    public function flush(): void
    {
        foreach ($this->entitiesForUpdates as $class => $entities) {
            $repo = $this->getRepository($class);

            foreach ($entities as $entity) {
                $id = $entity->getId();

                if ($id === null) {
                    // Neue Entity → INSERT
                    $repo->insert($entity);
                } else {
                    // Existierende Entity → UPDATE
                    $repo->update($entity);
                }
            }
        }

        // Danach alles zurücksetzen
        $this->entitiesForUpdates = [];
    }

}
