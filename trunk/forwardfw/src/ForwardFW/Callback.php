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
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009,2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

/**
 * Config for a Cache.
 *
 * @category   Config
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Callback
{
    /**
     * @var Callback The callback function configuration
     */
    public $callback = null;

    /**
     * @var array Array with the parameters for function call.
     */
    public $arParameters = null;

    /**
     * Constructor with validation of $callback
     *
     * @param Callback $callback     The callback function.
     * @param Array    $arParameters Parameters for function call.
     *
     * @return void
     * @throws ForwardFW_Exception_NoCallbackFunction
     */
    public function __construct($callback, array $arParameters = null)
    {
        if (is_callable($callback, true)) {
            $this->callback = $callback;
            $this->setParameters($arParameters);
        } else {
            // @TODO throw
        }
    }

    /**
     * Sets the parameters array for callback.
     *
     * @param Array $arParameters Parameters for function call.
     *
     * @return ForwrdFW_Callback
     */
    public function setParameters(array $arParameters = null)
    {
        $this->arParameters = $arParameters;
    }

    /**
     * Do the callback
     *
     * @return mixed Depends on function call.
     */
    public function doCallback()
    {
        if (is_null($this->arParameters)) {
            return call_user_func($this->callback);
        } else {
            return call_user_func_array($this->callback, $this->arParameters);
        }
    }
}
?>