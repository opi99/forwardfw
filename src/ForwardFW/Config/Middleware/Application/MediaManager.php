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
class MediaManager extends Application
{
    /**
     * @var string $executionClassName
     */
    protected string $executionClassName = \ForwardFW\Middleware\Application\MediaManager::class;

    /**
     * @var string Name of the application
     */
    private string $name = 'MediaManager';

    /**
     * @var string Identity of the application for get/post parameters
     */
    private string $ident = '';

    private string $storagePath = '/';

    private string $publicPath = '/';

    private int $maxFileSize = 1024 * 1024; // 1 MB
    
    public function getStoragePath(): string
    {
        return $this->storagePath;
    }
    
    public function setStoragePath(string $storagePath): self
    {
        $this->storagePath = $storagePath;
        return $this;
    }

    public function getPublicPath(): string
    {
        return $this->publicPath;
    }

    public function setPublicPath(string $publicPath): self
    {
        $this->publicPath = $publicPath;
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
