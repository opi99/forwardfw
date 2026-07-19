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
use ForwardFW\Service\MediaService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This Controller over one application.
 */
class UploadApplication extends \ForwardFW\Middleware
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
    protected function validateUploads(UploadedFileInterface $uploadedFile, MediaService $mediaService): array
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

        if ($uploadedFile->getSize() > $this->config->getMaxFileSize()) {
            throw new \Exception('File too large');
        }

        $originalFileName = $uploadedFile->getClientFilename();
        $originalFileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        return $mediaService->validateMimeTypeAndExtension($allowed, $originalFileName, $uploadedFile->getStream());
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

    protected function upload(ServerRequestInterface $request): array
    {
        $success = true;
        $uploadedFiles = $request->getUploadedFiles();

        if (!isset($uploadedFiles['file'])) {
            throw new \Exception('No file uploaded');
        }

        $uploadedFilesArray = is_array($uploadedFiles['file']) ? $uploadedFiles['file'] : [$uploadedFiles['file']];
        $mediaService = $this->serviceManager->getService(\ForwardFW\Service\MediaServiceInterface::class);

        foreach ($uploadedFilesArray as $uploadedFile) {
            try {
                // Entity erstellen
                $media = new Media();

                $resolvedMimeInfo = $this->validateUploads($uploadedFile, $mediaService);
                $tmpFile = $this->moveUploadToTmp($uploadedFile);

                $media = $mediaService->addMedia($tmpFile, $uploadedFile->getClientFilename(), $this->config->getStorageIdentifier(), $resolvedMimeInfo);
            } catch (\Exception $e) {
                $success = false;
            } finally {
                if (is_file($tmpFile)) {
                    unlink($tmpFile);
                }
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
