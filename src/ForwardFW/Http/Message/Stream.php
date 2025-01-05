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
        rewind($this->stream);
    }

    public function isWritable()
    {
        return true;
    }

    public function write($string)
    {
        return fwrite($this->stream, $string) || 0;
    }

    public function isReadable()
    {
        return false;
    }

    public function read($length)
    {
        return 0;
    }

    public function getContents()
    {
        return stream_get_contents($this->stream);
    }

    public function getMetadata($key = null)
    {
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
