<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category   Request
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

/**
 * This class represents the Response to the browser.
 *
 * @category   Request
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
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
    private $strContentType = '';

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
     * @return mixed The data themself
     */
    public function getData($key)
    {
        return ($this->data[$key]);
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
     * Sets the HTTP Status Code
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
     * Sets the HTTP ContentType
     *
     * @param string $strContentType The HTTP ContentType
     *
     * @return ForwardFW_Response Themself.
     */
    public function setContentType($strContentType)
    {
        $this->strContentType = $strContentType;
        return $this;
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
        if ($this->strContentType) {
            header('Content-Type: ' . $this->strContentType);
        }
        if (null !== $this->strContentDisposition) {
            header('Content-Disposition: ' . $this->strContentDisposition);
        }
        echo $this->content;
    }
}
