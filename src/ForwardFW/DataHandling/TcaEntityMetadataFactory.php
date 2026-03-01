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
 * Factory for EntityMetadata from TYPO3 like TCA
 */
class TcaEntityMetadataFactory
    extends EntityMetadataFactoryAbstract
{
    private array $tca = [];

    protected function buildMetadataFor(string $entityName): EntityMetadata
    {
        $this->loadAllTca();

        if (!isset($this->tca[$entityName])) {
            throw new \Exception('Entity not configured');
        }

        $localTca = $this->tca[$entityName];

        $fieldConfiguration = $this->buildFields($localTca['columns'], $localTca['ctrl']);

        return new EntityMetadata(
            $localTca['ctrl']['table'],
            $localTca['ctrl']['entity'],
            $localTca['ctrl']['identityField'] ?? 'uid',
            $localTca['ctrl']['identityFieldPublic'] ?? null,
            $fieldConfiguration['fields'],
            $fieldConfiguration['relations'],
        );
    }

    protected function buildFields(array $columns, array $ctrl): array
    {
        $fields = [
            'fields' => [],
            'relations' => [],
        ];

        foreach ($columns as $name => $column) {
            $config = $column['config'] ?? [];
            $type = $config['type'] ?? 'input';
            $isRelation = $this->isRelation($type);
            $isIdentifier = $this->isIdentifier($name, $ctrl);

            $fields['fields'][$name] = new FieldMetadata(
                $name,
                $type,
                $isRelation,
                $isIdentifier,
                $config,
            );

            if ($isRelation) {
                $fields['relations'][] = $name;
            }
        }

        return $fields;
    }

    private function isRelation(string $fieldType): bool
    {
        return in_array(
            $fieldType,
            ['inline', 'select', 'group'],
            true
        );
    }

    private function isIdentifier(string $fieldName, array $ctrl): bool
    {
        return ($fieldName === ($ctrl['identityField'] ?? 'uid'))
            || ($fieldName === ($ctrl['identityFieldPublic'] ?? null));
    }

    private function loadAllTca()
    {
        if (!empty($this->tca)) {
            return;
        }

        /** @TODO Caching in one array */
        foreach (glob($this->config->getTcaPath() . '/*.php') as $file) {
            $localTca = include $file;
            $this->tca[$localTca['ctrl']['entity']] = $localTca;
        }
    }
}
