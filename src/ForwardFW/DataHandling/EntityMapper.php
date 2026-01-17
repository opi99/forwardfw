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
class EntityMapper
    implements EntityMapperInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly EntityMetadata $entityMetadata,
    ) {

    }

    public function mapCollection(array $data): array
    {
        $collection = [];

        foreach ($data as $values) {
            $collection[] = $this->mapEntity($values);
        }

        return $collection;
    }

    public function mapEntity(array $values): object
    {
        $entityClassName = $this->entityMetadata->getEntityClassName();
        $identifierField = $this->entityMetadata->getIdentifierField();
        $identifier = $values[$identifierField];

        if ($this->entityManager->has($entityClassName, $identifier)) {
            return $this->entityManager->get($entityClassName, $identifier);
        }

        $entity = new $entityClassName();

        $this->entityManager->register($entityClassName, $identifier, $entity, $values);

        foreach ($this->entityMetadata->getFieldsMetadata() as $fieldName => $fieldMeta) {
            $value = $values[$fieldName];
            if ($fieldMeta->isRelation()) {
                $value = $this->resolveRelation($fieldMeta, $values[$fieldName]);
            }
            $functionName = 'set' . ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName))));
            $entity->$functionName($value);
        }

        return $entity;
    }

    protected function resolveRelation(FieldMetadata $fieldMetadata, mixed $value): object
    {
        $config = $fieldMetadata->getConfig();

        // 1:1 Mapper, for the moment
        $foreignEntityClassName = $config['foreign_entity'];
        $identifier = $value;

        if ($this->entityManager->has($foreignEntityClassName, $identifier)) {
            return $this->entityManager->get($foreignEntityClassName, $identifier);
        } else {
            return $this->entityManager->load($foreignEntityClassName, $identifier);
        }
    }
}
