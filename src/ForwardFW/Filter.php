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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009,2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

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
abstract class ForwardFW_Filter
{
    /**
     * Child filter that should be executed after your Incoming process.
     *
     * @var ForwardFW_Filter
     */
    protected $child = null;

    /**
     * Constructor
     *
     * @param ForwardFW_Filter $_child The child filter or null if you are the last
     *
     * @return new instance
     */
    public function __construct(ForwardFW_Filter $_child = null)
    {
        $this->child = $_child;
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
     * Function to process filtering incoming/child/outgoing
     *
     * @return void
     */
    public function doFilter()
    {
        $this->doIncomingFilter();
        if (!is_null($this->child)) {
            $this->child->doFilter();
        }
        $this->doOutgoingFilter();
    }
}

?>