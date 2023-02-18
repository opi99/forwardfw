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

use Psr\Http\Message\ResponseInterface;

/**
 * This class represents the Response to the browser.
 */
class Response
    extends Message
    implements ResponseInterface
{
    /** @var \ForwardFW\Object\Stateless\Timer Holds every Log message as string. */
    private $logTimer = null;

    /** @var \ForwardFW\Object\Stateless\Timer Holds every Error message as string. */
    private $errorTimer = null;

    /** @var integer HTTP Status Code. */
    private $httpStatusCode = 200;

    /** @var string HTTP Status Message. */
    private $httpStatusMessage = '';

    /** @var string Type of content */
    private $contentType = 'text/plain';

    /** @var string Holds the content to send back to web server. */
    private $content = '';

    /** @var string The HTTP ContentDisposition */
    private $contentDisposition = null;

    /** @var \ArrayObject data */
    private $data = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->logTimer = new \ForwardFW\Object\Stateless\Timer();
        $this->errorTimer = clone $this->logTimer;
        $this->logTimer->addEntry('Started after: ' . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));
        $this->data = new \ArrayObject();
    }

    /**
     * Adds an entry to the log array.
     *
     * @param string $strEntry The entry as string.
     */
    public function addLog(string $strEntry): self
    {
        $this->logTimer->addEntry($strEntry);
        return $this;
    }

    /**
     * Sets data into response. Please NameSpace your keys!
     *
     * @param string $key Name of the data
     * @param mixed $value The data themself
     */
    public function addData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Gets data which was set into response. Please NameSpace your keys!
     *
     * @param string $key Name of the data
     */
    public function getData(string $key)
    {
        return $this->data[$key];
    }

    /**
     * Gets all data which was set into response.
     *
     * @return mixed The data themself
     */
    public function getAllData()
    {
        return $this->data;
    }

    /**
     * Adds an entry to the error array.
     *
     * @param string $errorMessage The entry as string.
     * @param integer $httpStatusCode The HTTP Status Code
     */
    public function addError(string $errorMessage, int $httpStatusCode = 0): self
    {
        $this->errorTimer->addEntry($errorMessage);
        if ($httpStatusCode !== 0) {
            $this->setHttpStatus($httpStatusCode, $errorMessage);
        } else {
            if ($this->httpStatusCode === 200) {
                $this->setHttpStatus(500);
            }
        }
        return $this;
    }

    /**
     * Sets the HTTP ContentType
     *
     * @param string $contentType The HTTP ContentType
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Gets the HTTP ContentType
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sets the HTTP ContentDisposition
     *
     * @param string $contentDisposition The HTTP ContentDisposition
     */
    public function setContentDisposition(string $contentDisposition): self
    {
        $this->contentDisposition = $contentDisposition;
        return $this;
    }

    /**
     * Gets the HTTP ContentDisposition
     */
    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    /**
     * Returns the array with all its log entries.
     */
    public function getErrors(): \ForwardFW\Object\Stateless\Timer
    {
        return $this->errorTimer;
    }

    /**
     * Returns the array with all its log entries.
     */
    public function getLogs(): \ForwardFW\Object\Stateless\Timer
    {
        return $this->logTimer;
    }

    /**
     * Returns the content, which should be send back to web server.
     */
    public function getContent(): string
    {
        return $this->content;
    }


    // NEW
    /**
     * Gets the HTTP Status Code
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Sets the HTTP Status Code and Message
     *
     * @param int $code The HTTP Status Code (3-digits)
     * @param string $reasonPhrase The HTTP Status Message
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $clone = clone $this;
        $clone->httpStatusCode = $code;
        $clone->httpStatusMessage = $reasonPhrase;
        return $clone;
    }


    /**
     * Gets the HTTP Status Message
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        return $this->httpStatusMessage;
    }
}
