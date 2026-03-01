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

namespace ForwardFW\Form;

use ForwardFW\DataHandling\FieldMetadata;

class Node
{
    public function __construct(
        public readonly FieldMetadata $metadata,
        public string $htmlName,
        public string $renderType,
        public mixed $value,
        public array $attributes = [],
        public array $choices = [],
        public array $children = [],
    ) {}

    public function getMetadata(): FieldMetadata
    {
        return $this->metadata;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}
