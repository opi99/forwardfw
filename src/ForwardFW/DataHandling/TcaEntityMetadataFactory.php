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

        return new EntityMetadata(
            $localTca['ctrl']['table'],
            $localTca['ctrl']['entity'],
            $localTca['columns'],
        );
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
