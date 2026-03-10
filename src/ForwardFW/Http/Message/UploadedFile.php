<?php

declare(strict_types=1);

namespace ForwardFW\Http\Message;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

class UploadedFile implements UploadedFileInterface
{
    protected ?StreamInterface $stream = null;
    protected ?string $fileName = null;
    protected int $size;
    protected int $error;
    protected ?string $clientFilename;
    protected ?string $clientMediaType;
    
    protected bool $moved = false;

    public function __construct(
        StreamInterface|string $streamOrFile,
        ?int $size = null,
        int $error = \UPLOAD_ERR_OK,
        ?string $clientFilename = null,
        ?string $clientMediaType = null
    ) {
        if ($streamOrFile instanceof StreamInterface) {
            $this->stream = $streamOrFile;
        } else {
            $this->fileName = $streamOrFile;
        }
        $this->size = $size ?? ($this->stream ? $this->stream->getSize() : 0);
        $this->error = $error;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream(): StreamInterface
    {
        if ($this->moved) {
            throw new \RuntimeException('File already moved.');
        }

        if ($this->stream) {
            return $this->stream;
        }

        $this->stream = new Stream($this->fileName);
        return $this->stream;
    }

    public function moveTo($targetPath): void
    {
        if ($this->moved) {
            throw new \RuntimeException('File already moved.');
        }

        if (!empty($this->fileName)) {
            if (!is_uploaded_file($this->fileName)) {
                throw new \RuntimeException('File was not uploaded');
            }
            if (!move_uploaded_file($this->fileName, $targetPath)) {
                throw new \RuntimeException('Could not move uploaded');
            }
            $this->moved = true;
        } else {
            throw new \RuntimeException('Save Upload stream not supported yet');
        }
    }

    public function getSize(): ?int
    {
        return $this->size; 
    }

    public function getError(): int 
    {
        return $this->error; 
    }

    public function getClientFilename(): ?string 
    { 
        return $this->clientFilename; 
    }

    public function getClientMediaType(): ?string 
    {
        return $this->clientMediaType;
    }
}
