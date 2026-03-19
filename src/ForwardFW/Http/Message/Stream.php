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

namespace ForwardFW\Http\Message;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    /** @var resource */
    protected $stream;

    public function __construct($stream, string $mode = 'r')
    {
        if (is_resource($stream)) {
            $this->stream = $stream;
        } elseif (is_string($stream)) {
            $this->stream = fopen($stream, $mode) ?: null;
        } else {
            throw new \InvalidArgumentException('Stream must be a string with stream identifier or resource');
        }
    }

    public function __toString()
    {
        $this->rewind();
        return $this->getContents();
    }

    public function close()
    {
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        $stream = $this->stream;
        unset($this->stream);

        return $stream;
    }

    public function getSize()
    {
    }

    public function tell()
    {
        return 0;
    }

    public function eof()
    {
        return true;
    }

    public function isSeekable()
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
    }

    public function rewind()
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        rewind($this->stream);
    }

    public function isWritable()
    {
        return true;
    }

    public function write($string)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        return fwrite($this->stream, $string) || 0;
    }

    public function isReadable()
    {
        return false;
    }

    public function read($length)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        if (0 === $length) {
            return '';
        }

        try {
            $data = fread($this->stream, $length);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to read from stream', 0, $e);
        }

        if (false === $data) {
            throw new \RuntimeException('Unable to read from stream');
        }

        return $data;
    }

    public function getContents()
    {
        return stream_get_contents($this->stream);
    }

    public function getMetadata($key = null)
    {
        if (!isset($this->stream)) {
            throw new \RuntimeException('Stream is detached');
        }

        $metadata = stream_get_meta_data($this->stream);
        if ($key === null) {
            return $metadata;
        }
        if (!isset($metadata[$key])) {
            return null;
        }
        return $metadata[$key];
    }
}
