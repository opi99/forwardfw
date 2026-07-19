<?php

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

namespace ForwardFW\Config\Service;

/**
 * Config for the Media Service.
 */
class MediaServiceConfig extends \ForwardFW\Config\Service
{
    protected string $executionClassName = \ForwardFW\Service\MediaService::class;
    protected string $interfaceName = \ForwardFW\Service\MediaServiceInterface::class;

    protected array $storages = [];

    public function addStorage(string $storageIdentifier, string $storagePath, string $publicPath): self
    {
        /** @TODO StorageConfigClass */
        $this->storages[$storageIdentifier] = [
            'driver' => 'local',
            'storagePath' => $storagePath,
            'publicPath' => $publicPath,
        ];
        return $this;
    }

    public function getStorage(string $storageIdentifier): array
    {
        return $this->storages[$storageIdentifier] ?? [];
    }

    public function getStorages(): array
    {
        return $this->storages;
    }
}
