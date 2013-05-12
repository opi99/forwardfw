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
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

require_once 'ForwardFW/Object/Timer.php';

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
     * Holds every Log message as string.
     *
     * @var ForwardFW\Object\Timer
     */
    private $logTimer = null;

    /**
     * Holds every Error message as string.
     *
     * @var ForwardFW\Object\Timer
     */
    private $errorTimer = null;

    /**
     * Holds the content to send back to web server.
     *
     * @var string
     */
    private $strContent = '';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->logTimer   = new Object\Timer();
        $this->errorTimer = clone $this->logTimer;
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
     * Adds an entry to the error array.
     *
     * @param string $strEntry The entry as string.
     *
     * @return ForwardFW_Response Themself.
     */
    public function addError($strEntry)
    {
        $this->errorTimer->addEntry($strEntry);
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
}
