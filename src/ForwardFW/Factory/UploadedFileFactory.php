<?php

declare(strict_types=1);

namespace ForwardFW\Factory;

use ForwardFW\Http\Message\UploadedFile;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFileFactory implements UploadedFileFactoryInterface
{
    public function createUploadedFile(
        StreamInterface $stream,
        ?int $size = null,
        int $error = \UPLOAD_ERR_OK,
        ?string $clientFilename = null,
        ?string $clientMediaType = null
    ): UploadedFileInterface {
        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }

    public static function createFromGlobals(?array $files = null): array
    {
        $files = $files ?? $_FILES;
        $uploadedFiles = [];

        foreach ($files as $key => $file) {
            if (is_array($file['tmp_name'])) {
                $uploadedFiles[$key] = [];
                foreach ($file['tmp_name'] as $key => $tmpName) {
                    $uploadedFiles[$key][] = new UploadedFile(
                        $file['tmp_name'][$key],
                        $file['size'][$key],
                        $file['error'][$key],
                        $file['name'][$key],
                        $file['type'][$key]
                    );
                }
            } else {
                $uploadedFiles[$key] = new UploadedFile(
                    $file['tmp_name'],
                    $file['size'],
                    $file['error'],
                    $file['name'],
                    $file['type']
                );
            }
        }

        return $uploadedFiles;
    }
}
