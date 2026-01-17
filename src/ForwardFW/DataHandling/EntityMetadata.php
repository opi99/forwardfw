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
 * Metadata for an entity to get it mapped/managed
 */
class EntityMetadata
{
    public function __construct(
        public readonly string $tableName,
        public readonly string $entityClassName,
        public readonly string $identifierField,
        public readonly array $fieldsMetadata,
    ) {

    }

    /** @TODO Needed? */
    public function getRealName(): string
    {
        return $this->entityClassName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getIdentifierField(): string
    {
        return $this->identifierField;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function getFieldsMetadata(): array
    {
        return $this->fieldsMetadata;
    }

    public function getRepositoryClassname(): ?string
    {
        return null;
    }
}
