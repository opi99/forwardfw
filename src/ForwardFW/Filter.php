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
 * @category   Filter
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
 * This abstract class needs to be extended to be a callable filter.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
abstract class Filter
{
    /**
     * Child filter that should be executed after your Incoming process.
     *
     * @var ForwardFW_Filter
     */
    protected $child = null;

    /**
     * Set to true if the Chain should stop here.
     *
     * @var boolean
     */
    protected $doStopChain = false;

    /**
     * Constructor
     *
     * @param ForwardFW\Filter $child The child filter or null if you are the last
     *
     * @return new instance
     */
    public function __construct(Filter $child = null)
    {
        $this->child = $child;
    }

    /**
     * Function to process before your child
     *
     * @return void
     */
    abstract public function doIncomingFilter();

    /**
     * Function to process after your child
     *
     * @return void
     */
    abstract public function doOutgoingFilter();

    /**
     * Sets a child for the filter.
     *
     * @param ForwardFW\Filter $child The child filter or null if you are the last
     *
     * @return void
     */
    public function setChild(Filter $child = null)
    {
        $this->child = $child;
    }

    /**
     * Function to process filtering incoming/child/outgoing
     *
     * @return void
     */
    public function doFilter()
    {
        $this->doIncomingFilter();
        if (!is_null($this->child) && !$this->doStopChain) {
            $this->child->doFilter();
        }
        $this->doOutgoingFilter();
    }
}
