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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.11
 */

namespace ForwardFW\Config;

/**
 * Config for a Application Filter.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Config
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Application extends \ForwardFW\Config
{
    use \ForwardFW\Config\Traits\Execution;

    /**
     * @var string $executionClassName
     */
    protected $executionClassName = 'ForwardFW\\Controller\\Application';

    /**
     * @var string Name of the application
     */
    private $strName = '';

    /**
     * @var array Screens of the application
     */
    private $arScreens = '';

    /**
     * @var string Identity of the application for get/post parameters
     */
    private $strIdent = '';

    /**
     * @var ForwardFW\Config\Templater
     */
    private $templaterConfig = null;

    /**
     * Sets name of the application.
     *
     * @param string $strName Name of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setName($strName)
    {
        $this->strName = $strName;
        return $this;
    }

    /**
     * Sets screens of the application
     *
     * @param array $arScreens Screens of the application.
     *
     * @return ForwardFW\Config\Application
     */
    public function setScreens(array $arScreens)
    {
        $this->arScreens = $arScreens;
        return $this;
    }

    /**
     * Sets the ident of the application.
     *
     * @param string $strIdent Identity of the application for post/get parameters.
     *
     * @return ForwardFW\Config\Application
     */
    public function setIdent($strIdent)
    {
        $this->strIdent = $strIdent;
        return $this;
    }

    public function setTemplaterConfig(Templater $templaterConfig)
    {
        $this->templaterConfig = $templaterConfig;
        return $this;
    }

    /**
     * Get name of the application.
     *
     * @return string
     */
    public function getName()
    {
        return $this->strName;
    }

    /**
     * Get screens of the application
     *
     * @return array
     */
    public function getScreens()
    {
        return $this->arScreens;
    }

    /**
     * Get ident of the application.
     *
     * @return string
     */
    public function getIdent()
    {
        return $this->strIdent;
    }

    /**
     * Get config of Templater
     *
     * @return string
     */
    public function getTemplaterConfig()
    {
        return $this->templaterConfig;
    }
}
