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

namespace ForwardFW\Object\Stateless;

/**
 * Model for mesuring time, can add hints with meantime
 */
class Timer extends \ForwardFW\Object\Stateless
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
     * @param string $strName Name to identify Timer on output
     *
     * @return void
     */
    public function __construct($strName = '')
    {
        parent::__construct();
        $this->strName = $strName;
        $this->nTimeStart = microtime(true);
    }

    /**
     * Set new start time and resets entries
     *
     * @return ForwardFW_Object_Timer The timer object
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
    public function getEntries()
    {
        return $this->arEntries;
    }
}
