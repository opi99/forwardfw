<?php
declare(encoding = "utf-8");
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
 * @category   Object
 * @package    ForwardFW
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

require_once 'ForwardFW/Object.php';

/**
 * Model for mesuring time, can add hints with meantime
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Object_Timer extends ForwardFW_Object
{
    /**
     * Name of this Timer for output
     *
     * @var string
     */
    private $strName = '';

    /**
     * Start time of this timer, microsecond
     *
     * @var float
     */
    private $nTimeStart = -1;

    /**
     * Endtime of this timer, microsecond
     *
     * @var float
     */
    private $nTimeStop = -1;

    /**
     * List of informational entries
     *
     * @var array of string
     */
    private $arEntries = array();

    /**
     * Starts the timer
     *
     * @param string $_strName
     *
     * @return new instance
     */
    public function __construct($_strName = '') {
        parent::__construct();
        $this->strName = $_strName;
        $this->nTimeStart = microtime(true);
    }

    /**
     * Set new start time and resets entries
     *
     * @param string $_strName
     *
     * @return ForwardFW_Object_Timer
     */
    public function reStart()
    {
        $this->nTimeStart = microtime(true);
        $this->nTimeStop = -1;
        $this->arEntries = array();

        return $this;
    }

    /**
     * Adds an entry for information output with elapsed time.
     *
     * @param string $strEntry The string to add.
     *
     * @return ForwardFW_Object_Timer
     */
    public function addEntry($strEntry)
    {
        $strElapsedTime = $this->getElapsedTime();
        array_push($this->arEntries, $strElapsedTime . ': ' . $strEntry);

        return $this;
    }

    /**
     * Give elapsed time since start of timer
     *
     * @return float Time in ms.
     */
    public function getElapsedTime()
    {
        $nNow = microtime(true);
        return $this->getTimeDifference($this->nTimeStart, $nNow);
    }

    /**
     * Give difference between microtimes in ms.
     *
     * @param float $nTimeStart Starttime for difference
     * @param float $nTimeStop  Endtime for difference
     *
     * @return float Time in ms.
     */
    public static function getTimeDifference($nTimeStart, $nTimeStop)
    {
        return ($nTimeStop - $nTimeStart) * 1000;
    }

    /**
     * Stops the timer
     *
     * @return ForwardFW_Object_Timer
     */
    public function stop()
    {
        $this->nTimeStop = microtime(true);
        return $this;
    }


    /**
     * Gives time between start and stop.
     *
     * @return float Time in ms.
     */
    protected function getStopTime()
    {
        return $this->getTimeDifference($this->nTimeStart, $this->nTimeStop);
    }

    /**
     * Return execution information
     *
     * @return string execution info
     */
    public function toString()
    {
        $strEntry = implode(', ', $this->arEntries);
        return '<br />' . $this->getStopTime() . ' ms'
            . ' - ' . $this->strName
            . ($strEntry != '' ? ' (' . $strEntry . ')' : '');
    }

    /**
     * Gives the array with all entries till yet.
     *
     * @return array of String with entries.
     */
    function getEntries()
    {
        return $this->arEntries;
    }
}

?>