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
 * Metadata for a field to get it mapped
 */
class FieldMetadata
{
    public function __construct(
        public readonly string $fieldName,
        public readonly string $dataType,
        public readonly string $phpType,
        public readonly string $uiType,
        public readonly bool $isRelation,
        public readonly bool $isIdentifier,
        public readonly array $config,
    ) {

    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getPhpType(): string
    {
        return $this->phpType;
    }

    public function getUiType(): string
    {
        return $this->uiType;
    }

    public function isRelation(): bool
    {
        return $this->isRelation;
    }

    public function isIdentifier(): bool
    {
        return $this->isIdentifier;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
