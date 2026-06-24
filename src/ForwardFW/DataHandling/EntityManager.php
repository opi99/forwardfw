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

    public function updateOriginalData(object $entity, array $dataOriginal): void
    {
        $entityName = get_class($entity);
        $identifier = $this->getIdentifier($entity);
        $this->entitiesDataOriginal[$entityName][$identifier] = $dataOriginal;
    }

    public function getIdentifier(object $entity): int|string|null
    {
        $class = get_class($entity);
        $metadata = $this->getMetadata($class);
        $identifierField = $this->getMetadata($class)->getIdentifierField();

        $identifierMethod = EntityHelper::getterForProperty($entity, $identifierField);

        return $entity->$identifierMethod();
    }

    public function persist(object $entity): void
    {
        $class = get_class($entity);
        $metadata = $this->getMetadata($class);

        $id = $this->getIdentifier($entity);

        if ($metadata->hasFieldsRelation()) {
            $fieldsMetadata = $metadata->getFieldsMetadata();
            foreach ($metadata->getFieldsRelation() as $fieldName)
            {
                if ($fieldsMetadata[$fieldName]->getUiType() === 'inline') {
                    $relationMethod = EntityHelper::getterForProperty($entity, $fieldName);
                    $this->persist($entity->$relationMethod());
                }
            }
        }

        $publicIdField = $metadata->getIdentifierFieldPublic();

        if ($publicIdField) {
            $relationMethod = EntityHelper::getterForProperty($entity, $publicIdField);
            $publicId = $entity->$relationMethod();
            if ($publicId === '') {
                // Generate publicId
            }
        }

        $this->entitiesForUpdates[$class . '|' . ($id ?? 'NEW' . spl_object_id($entity))] = $entity;
    }

    public function flush(): void
    {
        foreach ($this->entitiesForUpdates as $updateIdent => $entity) {
            list($class, $id) = explode('|', $updateIdent, 2);
            $repo = $this->getRepository($class);

            if (is_numeric($id)) {
                // Existierende Entity → UPDATE
                $this->prepareUpdate($entity);
                $repo->update($entity);
            } else {
                // Neue Entity → INSERT
                $this->prepareInsert($entity);
                $repo->insert($entity);
            }
        }

        // Danach alles zurücksetzen
        $this->entitiesForUpdates = [];
    }

    protected function prepareUpdate(object $entity): void
    {
        $entityMetadata = $this->getMetadata(get_class($entity));
        $this->setChangeTimeField($entity, $entityMetadata);
        $this->setSlugFields($entity, $entityMetadata);
    }

    protected function prepareInsert(object $entity): void
    {
        $entityMetadata = $this->getMetadata(get_class($entity));
        $this->setIdentifierField($entity, $entityMetadata);
        $this->setIdentifierPublicField($entity, $entityMetadata);
        $this->setCreationTimeField($entity, $entityMetadata);
        $this->setChangeTimeField($entity, $entityMetadata);
        $this->setSlugFields($entity, $entityMetadata);
    }

    protected function setIdentifierField(object $entity, EntityMetadata $entityMetadata): void
    {
        $identifierField = $entityMetadata->getIdentifierField();
        $identifierFieldMetadata = $entityMetadata->getFieldMetadata($identifierField);
        $identifierMethod = EntityHelper::setterForProperty($entity, $identifierField);
        if ($identifierFieldMetadata->getUiType() === 'ULID') {
            $ulid = new \Ulid\Ulid();
            $entity->$identifierMethod(
                $ulid->generate()
            );
        }
        if ($identifierFieldMetadata->getUiType() === 'NanoID') {
            $entity->$identifierMethod(
                \Snortlin\NanoId\NanoId::nanoId()
            );
        }
    }

    protected function setIdentifierPublicField(object $entity, EntityMetadata $entityMetadata): void
    {
        $identifierFieldPublic = $entityMetadata->getIdentifierFieldPublic();
        if (null !== $identifierFieldPublic) {
            $identifierFieldPublicMetadata = $entityMetadata->getFieldMetadata($identifierFieldPublic);
            $identifierPublicMethod = EntityHelper::getterForProperty($entity, $identifierFieldPublic);
            if ($entity->$identifierPublicMethod() !== null) {
                return;
            }
            $identifierPublicMethod = EntityHelper::setterForProperty($entity, $identifierFieldPublic);
            if ($identifierFieldPublicMetadata->getUiType() === 'ULID') {
                $ulid = new \Ulid\Ulid();
                $entity->$identifierPublicMethod(
                    $ulid->generate()
                );
            }
            if ($identifierFieldPublicMetadata->getUiType() === 'NanoID') {
                $entity->$identifierPublicMethod(
                    \Snortlin\NanoId\NanoId::nanoId()
                );
            }
        }
    }

    protected function setCreationTimeField(object $entity, EntityMetadata $entityMetadata): void
    {
        $creationTimeField = $entityMetadata->getCreationTimeField();
        if (null !== $creationTimeField) {
            $creationTimeMethod = EntityHelper::setterForProperty($entity, $creationTimeField);
            $entity->$creationTimeMethod(time());
        }
    }

    protected function setChangeTimeField(object $entity, EntityMetadata $entityMetadata): void
    {
        $changeTimeField = $entityMetadata->getChangeTimeField();
        if (null !== $changeTimeField) {
            $creationTimeMethod = EntityHelper::setterForProperty($entity, $changeTimeField);
            $entity->$creationTimeMethod(time());
        }
    }

    protected function setSlugFields(object $entity, EntityMetadata $entityMetadata): void
    {
        foreach ($entityMetadata->getFieldsMetadata() as $fieldMetadata) {
            if ($fieldMetadata->getUiType() === 'slug') {
                $slugSource = '';
                foreach ($fieldMetadata->getConfig()['source'] as $sourceFieldName) {
                    $slugSourceMethod = EntityHelper::getterForProperty($entity, $sourceFieldName);
                    $slugSource .= $entity->$slugSourceMethod() . ' ';
                }

                $slug = SlugHelper::slugify(trim($slugSource), $fieldMetadata->getConfig());

                $slugMethod = EntityHelper::setterForProperty($entity, $fieldMetadata->getFieldName());
                $entity->$slugMethod($slug);
            }
        }
    }
}
