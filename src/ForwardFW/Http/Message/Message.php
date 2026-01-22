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

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message
    implements MessageInterface
{
    protected $body;

    protected $headers = [];

    protected string $protocolVersion = '1.0';

    /**
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion(string $version)
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    /**
     * @return string[][]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return bool
     */
    public function hasHeader(string $name)
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader(string $name)
    {
        return ($this->headers[strtolower($name)] ?? []);
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name)
    {
        $headers = ($this->headers[strtolower($name)] ?? []);
        return implode(',', $headers);
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
        $clone->headers[strtolower($name)] = $value;
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
        $name = strtolower($name);
        if (!isset($clone->headers[$name])) {
            $clone->headers[$name] = [$value];
        } else {
            $clone->headers[$name] = array_merge($clone->headers[$name], $value);
        }
        return $clone;
    }

    /**
     * @param string $name Case-insensitive header field name to remove.
     * @return static
     */
    public function withoutHeader($name)
    {
        $name = strtolower($name);
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
    }

    /**
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody()
    {
        if ($this->body === null) {
            $this->body = new Stream('php://temp', 'r+');
        }
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
