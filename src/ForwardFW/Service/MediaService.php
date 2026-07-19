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

namespace ForwardFW\Service;

use ForwardFW\Entity\Media;
use Psr\Http\Message\StreamInterface;

/**
 * Manages Media operations and storaged
 */
class MediaService
    extends AbstractService
    implements MediaServiceInterface
{
    public function __construct(\ForwardFW\Config\Service\MediaServiceConfig $config, \ForwardFW\ServiceManager $serviceManager)
    {
        parent::__construct($config, $serviceManager);
    }

    public function validateMimeTypeAndExtension(array $allowedMimeTypes, string $filename, ?StreamInterface $fileStream = null): array
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE + FILEINFO_EXTENSION);

        if ($fileStream) {
            $content = $fileStream->read(8192);
            $realMimeType = $finfo->buffer($content, FILEINFO_MIME_TYPE);
            $mimeTypeExtensions = explode('/', $finfo->buffer($content, FILEINFO_EXTENSION));
        } else {
            $realMimeType = $finfo->file($filename, FILEINFO_MIME_TYPE);
            $mimeTypeExtensions = explode('/', $finfo->file($filename, FILEINFO_EXTENSION));
        }

        if (!in_array($realMimeType, $allowedMimeTypes, true)) {
            throw new \Exception('Not supported client mime type: "' . $realMimeType . '"');
        }

        return [
            'file_extension' => $mimeTypeExtensions[0],
            'mime_type' => $realMimeType,
        ];
    }

    public function addMedia(string $tmpFile, string $origFileName, string $storageIdentifier, array $resolvedMimeInfo): Media
    {
        $storageConfig = $this->config->getStorage($storageIdentifier);

        if (empty($storageConfig)) {
            throw new \RuntimeException('No storage config for storage identifier: "' . $storageIdentifier . '"');
        }

        $media = new Media();
        $media->setMimeType($resolvedMimeInfo['mime_type']);

        /** @TODO Move into a driver for local files and "LocalUserDirectory" files */
        $storageTargetAndFileName = $this->calculateTargetFilePathAndName($origFileName, $resolvedMimeInfo['file_extension'], $storageConfig, $media);
        $this->processFileToRealLocation($tmpFile, $storageTargetAndFileName, $media);
        $this->persistMedia($media);

        return $media;
    }


    protected function processFileToRealLocation(string $tmpFile, string $targetFullName, Media $media)
    {
        /**
         * @TODO This only works for images, not every media
         * So, this should be moved into a pre process, so maybe we can also import tiff but save them as avif
         */
        try {
            $image = new \Imagick($tmpFile);
        } catch (\ImagickException $e) {
            throw new \Exception("Keine gültige Bilddatei");
        }
        $image->stripImage();
        $image->writeImage($targetFullName);

        $media->setWidth($image->getImageWidth());
        $media->setHeight($image->getImageHeight());
        $media->setSize(filesize($targetFullName));

        return $image;
    }

    protected function calculateTargetFilePathAndName(string $origFileName, string $calculatedFileExtension, $storageConfig, Media $media): string
    {
        $publicId = \Snortlin\NanoId\NanoId::nanoId();

        // cleanen Dateinamen aus Originalname
        $cleanFilename = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($origFileName, PATHINFO_FILENAME));
        $cleanFilename = trim(preg_replace('/-+/', '-', $cleanFilename), '-');

        // Unterordner aus der public_id
        $aa = substr($publicId, 0, 2);
        $bb = substr($publicId, 2, 2);

        // Storage-Pfad: /storage/user/aa/bb/filename.ext
        $baseStorage = rtrim($storageConfig['storagePath'], '/');
        $userPublicId = 'user123'; // @TODO später aus User-Entity
        $targetStoragePath = 'user/' . $userPublicId . '/' . $aa . '/' . $bb;
        $targetDir = $baseStorage . '/' . $targetStoragePath;
        $publicPath = rtrim($storageConfig['publicPath'], '/') . '/' . $targetStoragePath;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // endgültiger Dateiname
        $filename = $cleanFilename . '-' . $publicId . '.' . $calculatedFileExtension;

        $media->setPublicId($publicId);
        $media->setFilename($filename);
        $media->setExtension($calculatedFileExtension);
        $media->setPublicPath($publicPath);
        return $targetDir . '/' . $filename;
    }

    protected function persistMedia(Media $media)
    {
        // Persist
        $entityManager = $this->serviceManager->getService(\ForwardFW\DataHandling\EntityManagerInterface::class);
        $entityManager->persist($media);
        $entityManager->flush();
    }
}
