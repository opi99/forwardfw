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

namespace ForwardFW\Middleware\Application;

use ForwardFW\Controller\ApplicationAbstract;
use ForwardFW\Entity\Media;
use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This Controller over one application.
 */
class MediaManager extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $responseFactory = new ResponseFactory();
        $data = [];

        try {
            // Look for action
            $action = $this->getAction($request);
            // Run action
            $data = $this->$action($request);
        } catch (\Exception $e) {
            return $this->getJsonResponse(
                [
                    'error' => $e->getMessage(),
                    'success' => false
                ],
                500,
                $e->getMessage());
        }

        return $this->getJsonResponse($data);
        $requestTargetPath = $request->getRequestTarget();
    }
    
    public function getJsonResponse($data, int $httpCode = 200, string $reason = ''): ResponseInterface
    {
        $response = (new ResponseFactory())
            ->createResponse($httpCode, $reason)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
            
        $body = $response->getBody();
        $body->write(json_encode($data));

        return $response;
    }

    protected function getAction(ServerRequestInterface $request): string
    {
        switch ($request->getRequestTarget()) {
            case '/upload':
                return 'upload';
            default:
                throw new \Exception('Action not found');
        }

        return '';
    }

    /**
     * Implementation for only one file per upload in the field named 'file'.
     */
    protected function validateUploads(UploadedFileInterface $uploadedFile, Media $media): void
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error');
        }

        // MIME Validierung
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        $mimeType = $uploadedFile->getClientMediaType();
        if (!in_array($mimeType, $allowed, true)) {
            throw new \Exception('Not supported client mime type: ' . $mimeType);
        }

        $originalFileName = $uploadedFile->getClientFilename();

        $originalFileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        $finfo = new \finfo(FILEINFO_MIME_TYPE + FILEINFO_EXTENSION);

        $content = $uploadedFile->getStream()->read(8192);
        $realMimeType = $finfo->buffer($content, FILEINFO_MIME_TYPE);
        $mimeTypeExtensions = explode('/', $finfo->buffer($content, FILEINFO_EXTENSION));

        if (!in_array($realMimeType, $allowed, true)) {
            throw new \Exception('Not supported client mime type: ' . $realMimeType);
        }
        if ($realMimeType !== $mimeType) {
            throw new \Exception('MimeType Cheating?');
        }
        if (!in_array($originalFileExtension, $mimeTypeExtensions, true)) {
            throw new \Exception('File extension is not covered by file mime type');
        }

        if ($uploadedFile->getSize() > $this->config->getMaxFileSize()) {
            throw new \Exception('File too large');
        }
        
        $media->setMimeType($realMimeType);
    }
    
    protected function moveUploadToTmp(UploadedFileInterface $uploadedFile): string
    {
        $tmpFile = sys_get_temp_dir() . '/' . bin2hex(random_bytes(8));

        try {
            $uploadedFile->moveTo($tmpFile);
        } catch (\Throwable $e) {
            $stream = $uploadedFile->getStream();
            file_put_contents($tmpFile, $stream->getContents());
        }

        return $tmpFile;
    }

    protected function processFileToRealLocation(UploadedFileInterface $uploadedFile, string $tmpFile, string $targetFullName, Media $media): \Imagick
    {
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

    protected function calculateTargetFilePathAndName(UploadedFileInterface $uploadedFile, Media $media): string
    {
        $originalName = $uploadedFile->getClientFilename();

        $publicId = \Snortlin\NanoId\NanoId::nanoId();

        // cleanen Dateinamen aus Originalname
        $cleanFilename = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $cleanFilename = trim(preg_replace('/-+/', '-', $cleanFilename), '-');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Unterordner aus der public_id
        $aa = substr($publicId, 0, 2);
        $bb = substr($publicId, 2, 2);

        // Storage-Pfad: /storage/user/aa/bb/filename.ext
        $baseStorage = rtrim($this->config->getStoragePath(), '/');
        $userPublicId = 'user123'; // @TODO später aus User-Entity
        $targetStoragePath = 'user/' . $userPublicId . '/' . $aa . '/' . $bb;
        $targetDir = $baseStorage . '/' . $targetStoragePath;
        $publicPath = rtrim($this->config->getPublicPath(), '/') . '/' . $targetStoragePath;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // endgültiger Dateiname
        $filename = $cleanFilename . '-' . $publicId . '.' . $extension;

        $media->setPublicId($publicId);
        $media->setFilename($filename);
        $media->setExtension($extension);
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

    protected function upload(ServerRequestInterface $request): array
    {
        $success = true;
        $uploadedFiles = $request->getUploadedFiles();

        if (!isset($uploadedFiles['file'])) {
            throw new \Exception('No file uploaded');
        }

        $uploadedFilesArray = is_array($uploadedFiles['file']) ? $uploadedFiles['file'] : [$uploadedFiles['file']];

        foreach ($uploadedFilesArray as $uploadedFile) {
            try {
                // Entity erstellen
                $media = new Media();

                $this->validateUploads($uploadedFile, $media);
                $tmpFile = $this->moveUploadToTmp($uploadedFile);

                $targetFullName = $this->calculateTargetFilePathAndName($uploadedFile, $media);
                $this->processFileToRealLocation($uploadedFile, $tmpFile, $targetFullName, $media);
                unlink($tmpFile);
                $this->persistMedia($media);
            } catch (\Exception $e) {
                $success = false;
            }
        }

        // Currently expecting only one file
        // @TODO Return success/failed per upload file
        return [
            'success' => $success,
            'id' => ($success ? $media->getId() : 0),
            'url' => ($success ? $media->getPublicPath() . '/' . $media->getFilename() : ''), // Preview
        ];
    }
}
