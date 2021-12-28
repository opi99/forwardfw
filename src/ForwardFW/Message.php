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

namespace ForwardFW;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message
    implements MessageInterface
{
    protected $body;

    /**
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
    }

    /**
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        return $clone;
    }

    /**
     * @return string[][]
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return bool
     */
    public function hasHeader($name)
    {
        return false;
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {
        return [];
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name)
    {
        return '';
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        return $clone;
    }

    /**
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;
        return $clone;
    }

    /**
     * @param string $name Case-insensitive header field name to remove.
     * @return static
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;
        return $clone;
    }

    /**
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body Body.
     * @return static
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }
}
