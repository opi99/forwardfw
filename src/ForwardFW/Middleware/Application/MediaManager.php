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
use ForwardFW\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

    protected function upload(ServerRequestInterface $request): array
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (!isset($uploadedFiles['file'])) {
            throw new \Exception('No file uploaded');
        }

        $file = $uploadedFiles['file'];

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error');
        }

        // MIME Validierung
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = $file->getClientMediaType();
        if (!in_array($mimeType, $allowed, true)) {
            throw new \Exception('Invalid file type: ' . $mimeType);
        }
        
        $originalName = $file->getClientFilename();
        
        $publicId = \Snortlin\NanoId\NanoId::nanoId();

        // cleanen Dateinamen aus Originalname
        $cleanFilename = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $cleanFilename = trim(preg_replace('/-+/', '-', $cleanFilename), '-');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Unterordner aus der public_id
        $aa = substr($publicId, 0, 2);
        $bb = substr($publicId, 2, 2);
         
        // Storage-Pfad: /storage/user/aa/bb/filename.ext
        $baseStorage = $this->config->getStoragePath();
        $userPublicId = 'user123'; // @TODO später aus User-Entity
        $targetStoragePath = 'user/' . $userPublicId . '/' . $aa . '/' . $bb;
        $targetDir = $baseStorage . '/' . $targetStoragePath;
        $publicPath = rtrim($this->config->getPublicPath(), '/') . '/' . $targetStoragePath;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // endgültiger Dateiname
        $filename = $cleanFilename . '-' . $publicId . '.' . $extension;
        $targetFullName = $targetDir . '/' . $filename;

        $file->moveTo($targetFullName);

        // Bildinformationen
        $imageSize = getimagesize($targetFullName);
        if ($imageSize === false) {
            unlink($targetFullName);
            throw new \Exception('File is no image');            
        }

        $size = filesize($targetFullName);

        // Entity erstellen
        $media = new \ForwardFW\Entity\Media();
        $media->setPublicId($publicId);
        $media->setFilename($filename);
        $media->setExtension($extension);
        $media->setMimeType($mimeType);
        $media->setWidth($imageSize[0]);
        $media->setHeight($imageSize[1]);
        $media->setSize($size);
        $media->setPublicPath($publicPath);

        // Persist
        $entityManager = $this->serviceManager->getService(\ForwardFW\DataHandling\EntityManagerInterface::class);
        $entityManager->persist($media);
        $entityManager->flush();

        return [
            'success' => true,
            'id' => $media->getId(),
            'url' => $publicPath . '/' . $filename, // Preview
        ];
    }
}
