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

namespace ForwardFW\Config\Middleware\Application;

use ForwardFW\Config\Middleware\Application;

/**
 * Config for the MediaManager Application.
 */
class UploadApplicationConfig extends Application
{
    /**
     * @var string $executionClassName
     */
    protected string $executionClassName = \ForwardFW\Middleware\Application\UploadApplication::class;

    /**
     * @var string Name of the application
     */
    private string $name = 'UploadApplication';

    /**
     * @var string Identity of the application for get/post parameters
     */
    private string $ident = '';

    /**
     * @var string Identifier of the storage in the Media Service
     */
    private string $storageIdentifier = '';

    private int $maxFileSize = 1048576; // 1 MB
    
    public function getStorageIdentifier(): string
    {
        return $this->storageIdentifier;
    }
    
    public function setStorageIdentifier(string $storageIdentifier): self
    {
        $this->storageIdentifier = $storageIdentifier;
        return $this;
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    public function setMaxFileSize(int $maxFileSize): self
    {
        $this->maxFileSize = $maxFileSize;
        return $this;
    }

    /**
     * Get name of the application.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get ident of the application.
     *
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }
}
