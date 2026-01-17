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

        $data = $dataHandler->loadFromCached(
            'default',
            [
                'select' => '*',
                'from' => $tableName,
            ]
        );

        return $this->entityManager->getEntityMapper($this->entityMetadata->getRealName())->mapCollection($data);
    }

    public function findOne()
    {

    }

    public function countAll()
    {

    }
}
