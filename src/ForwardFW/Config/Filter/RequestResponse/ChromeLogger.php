<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @copyright  2009-2018 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.2.0
 */

namespace ForwardFW\Config\Filter\RequestResponse;

/**
 * Config for a Filter which outputs logging to ChromeLogger
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@googlemail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ChromeLogger extends \ForwardFW\Config\Filter\RequestResponse
{
    protected $executionClassName = \ForwardFW\Filter\RequestResponse\ChromeLogger::class;
}
