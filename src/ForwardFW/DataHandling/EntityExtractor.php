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
            $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
            if (method_exists($entity, $methodName)) {
                $values[$fieldName] = $entity->$methodName();
            } else {
                $values[$fieldName] = '';
            }
            if ($fieldMeta->isRelation() && is_object($values[$fieldName])) {
                $values[$fieldName] = $values[$fieldName]->getId();
            }
        }

        return $values;
    }
}
