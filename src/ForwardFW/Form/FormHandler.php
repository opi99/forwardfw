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

use ForwardFW\DataHandling\EntityManagerInterface;
use ForwardFW\DataHandling\EntityMetadata;
use ForwardFW\DataHandling\FieldMetadata;
use ForwardFW\ServiceManager;
use ForwardFW\Form\Form;

class FormHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->entityManager = $serviceManager->getService(\ForwardFW\DataHandling\EntityManagerInterface::class);
    }

    public function handle(Form $form, object $entity, array $data): object
    {
        $this->mapNodes($form->getNodes(), $entity, $data);

        return $entity;
    }

    protected function mapNodes(array $nodes, object $entity, array $data): void
    {
        foreach ($nodes as $node) {
            $fieldName = $node->getMetadata()->getFieldName();

            if (!array_key_exists($fieldName, $data)) {
                continue;
            }

            $value = $data[$fieldName];

            // Rekursiv bei Sub-Nodes
            if ($node->getChildren()) {
                $childEntity = $node->getValue();

                if ($childEntity !== null && is_array($value)) {
                    $this->mapNodes($node->getChildren(), $childEntity, $value);
                }

                continue;
            }

            if ($node->getMetadata()->isRelation()) {
                $foreignEntityName = $node->getMetadata()->getConfig()['foreign_entity'];
                // Only 1:1 relation
                $value = $this->entityManager->getRepository($foreignEntityName)->findByIdentifier($value);
            }

            $this->setValue($entity, $fieldName, $value);
        }
    }

    protected function setValue(object $entity, string $fieldName, mixed $value): void
    {
        $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));

        if (method_exists($entity, $methodName)) {
            $entity->$methodName($value);
        }
    }
}
