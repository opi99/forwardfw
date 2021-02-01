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

/**
 * This class represents the Response to the browser.
 */
class Response
{
    /**
     * @var ForwardFW\Object\Stateless\Timer Holds every Log message as string.
     */
    private $logTimer = null;

    /**
     * @var ForwardFW\Object\Stateless\Timer Holds every Error message as string.
     */
    private $errorTimer = null;

    /**
     * @var string Holds the content to send back to web server.
     */
    private $strContent = '';

    /**
     * @var integer HTTP Status Code.
     */
    private $httpStatusCode = 200;

    /**
     * @var string HTTP Status Message.
     */
    private $httpStatusMessage = '';

    /**
     * @var string Type of content.
     */
    private $contentType = 'text/plain';

    /**
     * @var string The HTTP ContentDisposition
     */
    private $strContentDisposition = null;

    /**
     * @var ArrayObject data
     */
    private $data = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->logTimer   = new Object\Stateless\Timer();
        $this->errorTimer = clone $this->logTimer;
        $this->logTimer->addEntry('Started after: ' . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));
        $this->data = new \ArrayObject();
    }

    /**
     * Adds an entry to the log array.
     *
     * @param string $strEntry The entry as string.
     *
     * @return ForwardFW_Response Themself.
     */
    public function addLog($strEntry)
    {
        $this->logTimer->addEntry($strEntry);
        return $this;
    }

    /**
     * Sets data into response. Please NameSpace your keys!
     *
     * @param string $key Name of the data
     * @param mixed $value The data themself
     * @return void
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Gets data which was set into response. Please NameSpace your keys!
     *
     * @param string $key Name of the data
     * @return mixed The data themself for given key
     */
    public function getData($key)
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
     *
     * @return ForwardFW_Response Themself.
     */
    public function addError($errorMessage, $httpStatusCode = 0)
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
     * Adds a string to the existent content string.
     *
     * @param string $strContent The content as string.
     *
     * @return ForwardFW_Response Themself.
     */
    public function addContent($strContent)
    {
        $this->content .= $strContent;
        return $this;
    }

    /**
     * Overwrite existent content string.
     *
     * @param string $content The content as string.
     *
     * @return ForwardFW_Response Themself.
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets the HTTP Status Code and Message
     *
     * @param integer $httpStatusCode The HTTP Status Code
     * @param string $httpStatusMessage The HTTP Status Message
     *
     * @return ForwardFW_Response Themself.
     */
    public function setHttpStatus($httpStatusCode, $httpStatusMessage = '')
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->httpStatusMessage = $httpStatusMessage;
        return $this;
    }

    /**
     * Gets the HTTP Status Code
     *
     * @return integer The HTTP Status Code
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Gets the HTTP Status Message
     *
     * @return string The HTTP Status Message
     */
    public function getHttpStatusMessage()
    {
        return $this->httpStatusMessage;
    }

    /**
     * Sets the HTTP ContentType
     *
     * @param string $contentType The HTTP ContentType
     *
     * @return ForwardFW_Response Themself.
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Gets the HTTP ContentType
     *
     * @return string The HTTP content type.
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the HTTP ContentDisposition
     *
     * @param string $strContentDisposition The HTTP ContentDisposition
     *
     * @return ForwardFW_Response Themself.
     */
    public function setContentDisposition($strContentDisposition)
    {
        $this->strContentDisposition = $strContentDisposition;
        return $this;
    }

    /**
     * Gets the HTTP ContentDisposition
     *
     * @return string The HTTP ContentDisposition
     */
    public function getContentDisposition()
    {
        return $this->strContentDisposition;
    }

    /**
     * Returns the array with all its log entries.
     *
     * @return ForwardFW_Object_Timer The entries in a Timer Object.
     */
    public function getErrors()
    {
        return $this->errorTimer;
    }

    /**
     * Returns the array with all its log entries.
     *
     * @return ForwardFW_Object_Timer The entries in a Timer Object.
     */
    public function getLogs()
    {
        return $this->logTimer;
    }

    /**
     * Returns the content, which should be send back to web server.
     *
     * @return string The content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sends content
     *
     * @return void
     */
    public function send()
    {
        header(
            'HTTP/1.1 ' . $this->httpStatusCode
            . ($this->httpStatusMessage !== '' ? ' ' . $this->httpStatusMessage : '')
        );
        if ($this->contentType) {
            header('Content-Type: ' . $this->contentType);
        }
        if (null !== $this->strContentDisposition) {
            header('Content-Disposition: ' . $this->strContentDisposition);
        }
        echo $this->content;
    }
}
