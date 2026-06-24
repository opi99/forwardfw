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
 * Entity to DataArray
 */
class EntityExtractor
    implements EntityExtractorInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly EntityMetadata $entityMetadata,
    ) {

    }

    public function extractEntity(object $entity, bool $partial = false): array
    {
        $entityClassName = $this->entityMetadata->getEntityClassName();
        $identifierField = $this->entityMetadata->getIdentifierField();

        $values = [];

        foreach ($this->entityMetadata->getFieldsMetadata() as $fieldName => $fieldMeta) {
            $methodNameGet = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
            $methodNameIs = 'is' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
            if (method_exists($entity, $methodNameGet)) {
                $values[$fieldName] = $entity->$methodNameGet();
            } elseif (method_exists($entity, $methodNameIs)) {
                $values[$fieldName] = $entity->$methodNameIs();
            } else {
                $values[$fieldName] = '';
            }
            if ($fieldMeta->isRelation()) {
                if (is_object($values[$fieldName])) {
                    $values[$fieldName] = $values[$fieldName]->getId();
                } else {
                    $values[$fieldName] = null;
                }
            } else {
                $values[$fieldName] = $this->castValue($values[$fieldName], $fieldMeta);
            }
        }

        return $values;
    }

    protected function castValue(mixed $value, FieldMetadata $fieldMeta): mixed
    {
        return match ($fieldMeta->getDataType()) {
            'int' => (int)$value,
            default => (string)$value,
        };
    }
}
