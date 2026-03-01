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

class FormBuilder
{
    private EntityManagerInterface $entityManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->entityManager = $serviceManager->getService(\ForwardFW\DataHandling\EntityManagerInterface::class);
    }

    public function getForm(string $entityName, ?object $entity = null): Form
    {
        $form = new Form(
            $this->buildNodes($entityName, $entity)
        );

        return $form;
    }

    protected function buildNodes(string $entityName, ?object $entity = null, ?string $parentHtmlName = null): array
    {
        $entityMetadata = $this->entityManager->getMetadata($entityName);
        $nodes = [];
        foreach ($entityMetadata->getFieldsMetadata() as $fieldMetadata) {
            $fieldHtmlName = $this->buildHtmlName($fieldMetadata, $parentHtmlName);
            $fieldRenderType = $this->buildRenderType($fieldMetadata, $entity);
            $fieldValue = $this->getFieldValue($fieldMetadata, $entity);
            $fieldAttributes = $this->buildAttributes($fieldMetadata, $fieldRenderType);
            $fieldChoices = $this->buildChoices($fieldMetadata);
            $fieldChildren = $this->buildChildren($fieldMetadata, $entity, $fieldHtmlName);
            $node = new Node(
                $fieldMetadata,
                $fieldHtmlName,
                $fieldRenderType,
                $fieldValue,
                $fieldAttributes,
                $fieldChoices,
                $fieldChildren
            );
            $nodes[] = $node;
        }
        return $nodes;
    }

    protected function buildHtmlName(FieldMetadata $fieldMetadata, ?string $parentHtmlName): string
    {
        if ($parentHtmlName === null) {
            return $fieldMetadata->getFieldName();
        }
        return $parentHtmlName . '[' . $fieldMetadata->getFieldName() . ']';
    }

    protected function buildRenderType(FieldMetadata $fieldMetadata, ?object $entity): string
    {
        $renderType = $fieldMetadata->getConfig()['renderType'] ?? $fieldMetadata->getConfig()['type'] ?? 'none';

        switch ($renderType) {
            case 'autoincrement':
            case 'ULID':
                if (null === $entity) {
                    // New => No Rendering
                    $renderType = 'none';
                } else {
                    $renderType = 'input';
                }
                break;
            default:
                // Nothing to do
        }

        return $renderType;
    }

    protected function buildAttributes(FieldMetadata $fieldMetadata, string $renderType): array
    {
        $attributes = [
            'required' => (bool)($fieldMetadata->getConfig()['required'] ?? false),
            'readonly' => (bool)($fieldMetadata->getConfig()['readonly'] ?? false),
        ];

        switch ($renderType) {
            case 'input':
                $attributes['size'] = (int)($fieldMetadata->getConfig()['size'] ?? 20);
                break;
            case 'text':
                $attributes['cols'] = (int)($fieldMetadata->getConfig()['cols'] ?? 25);
                $attributes['rows'] = (int)($fieldMetadata->getConfig()['rows'] ?? 5);
                break;
            default:
                // Empty by design
        }

        return $attributes;
    }

    protected function buildChoices(FieldMetadata $fieldMetadata): array
    {
        $choices = [];

        if ($fieldMetadata->getType() === 'select') {
            $foreignEntityName = $fieldMetadata->getConfig()['foreign_entity'];
            $foreignEntities = $this->entityManager->getRepository($foreignEntityName)->findAll();
            // $foreignEntityMetadata = $this->entityManager->getMetadata($foreignEntityName);

            foreach ($foreignEntities as $foreignEntity) {
                $choices[] = [
                    'id' => $foreignEntity->getId(),
                    'label' => $foreignEntity->getTitle(),
                ];
            }
        }
        return $choices;
    }

    protected function buildChildren(FieldMetadata $fieldMetadata, ?object $entity, string $parentHtmlName): array
    {
        $children = [];

        if ($fieldMetadata->isRelation() && $fieldMetadata->getType() === 'inline') {
            $foreignEntityName = $fieldMetadata->getConfig()['foreign_entity'];

            /** @TODO More then 1:1 relations */
            $foreignEntity = $this->getFieldValue($fieldMetadata, $entity);
            $children = $this->buildNodes($foreignEntityName, $foreignEntity, $parentHtmlName);
        }

        return $children;
    }

    protected function getFieldValue(FieldMetadata $fieldMetadata, ?object $entity): mixed
    {
        if (null === $entity) {
            return null;
        }
        $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldMetadata->getFieldName())));
        if (!method_exists($entity, $methodName)) {
            return null;
        }
        $value = $entity->$methodName();
        if ($fieldMetadata->isRelation()) {
            /** @TODO Check fieldname for id */
            $value = $value->getId();
        }
        return $value;
    }
}
