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
 * Manager for different entities
 */
class EntityRepository
    implements EntityRepositoryInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly EntityMetadata $entityMetadata,
    ) {

    }

    public function findAll()
    {
        $tableName = $this->entityMetadata->getTableName();
        $dataHandler = $this->entityManager->getServiceManager()->getService(\ForwardFW\Service\DataHandlerInterface::class);

        $data = $dataHandler->loadFrom(
            'default',
            [
                'select' => '*',
                'from' => $tableName,
            ]
        );

        return $this->entityManager->getEntityMapper($this->entityMetadata->getRealName())->mapCollection($data);
    }

    public function findByIdentifier(int|string $identifier): object
    {
        $tableName = $this->entityMetadata->getTableName();
        $identifierField = $this->entityMetadata->getIdentifierField();
        $dataHandler = $this->entityManager->getServiceManager()->getService(\ForwardFW\Service\DataHandlerInterface::class);

        $data = $dataHandler->loadFrom(
            'default',
            [
                'select' => '*',
                'from' => $tableName,
                'where' => $identifierField . '=' . $identifier,
                'limit' => 1,
            ]
        );

        return $this->entityManager->getEntityMapper($this->entityMetadata->getRealName())->mapEntity($data[0]);
    }

    public function findOne()
    {

    }

    public function countAll()
    {

    }

    public function insert(object $entity): void
    {
        $tableName = $this->entityMetadata->getTableName();
        $data = $this->entityManager->getEntityExtractor($this->entityMetadata->getRealName())->extractEntity($entity);
        $dataHandler = $this->entityManager->getServiceManager()->getService(\ForwardFW\Service\DataHandlerInterface::class);

        unset($data['id']);

        $id = $dataHandler->create(
            'default',
            [
                'to' => $tableName,
                'values' => $data,
                'returnId' => true,
            ]
        );

        $entity->setId($id);
        $this->entityManager->register($this->entityMetadata->getRealName(), $id, $entity, $data);
    }

    public function update(object $entity): void
    {
        $tableName = $this->entityMetadata->getTableName();
        $data = $this->entityManager->getEntityExtractor($this->entityMetadata->getRealName())->extractEntity($entity);
        $dataHandler = $this->entityManager->getServiceManager()->getService(\ForwardFW\Service\DataHandlerInterface::class);

        $id = $data['id'];
        unset($data['id']);

        $dataHandler->update(
            'default',
            [
                'to' => $tableName,
                'values' => $data,
            ]
        );

        $this->entityManager->updateOriginalData($entity, $data);
    }
}
