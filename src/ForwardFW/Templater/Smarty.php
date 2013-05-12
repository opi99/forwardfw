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
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Templater
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.2
 */

namespace ForwardFW\Templater;

/**
 * Class to use Smarty as Templater.
 *
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Templater
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Smarty extends \ForwardFW\Controller implements \ForwardFW\Templater\TemplaterInterface
{
    /**
     * @var Smarty The smarty instance
     */
    private $smarty = null;

    /**
     * @var array Blocks to show
     */
    private $arShowBlocks = array();

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application
    ) {
        parent::__construct($application);

        $arConfig = $GLOBALS[get_class($this)];

        $strCompilePath = $arConfig['CompilePath'];
        if (!is_dir($strCompilePath)) {
            mkdir($strCompilePath, 0770, true);
        }

        $this->smarty = new \Smarty();
        $this->smarty->setCompileDir($strCompilePath);
        $this->smarty->registerPlugin('block', 'block', array(&$this, 'smartyBlock'));
        $this->smarty->registerPlugin('function', 'texter', array(&$this, 'smartyTexter'));

        $this->strTemplatePath = $arConfig['TemplatePath'];
    }

    /**
     * Sets file to use for templating
     *
     * @param string $_strFile Complete path and filename.
     *
     * @return ForwardFW_Templater_Smarty The instance.
     */
    public function setTemplateFile($_strFile)
    {
        $this->strFile = $_strFile;
        return $this;
    }

    /**
     * Sets a var in the template to a value
     *
     * @param string $_strName Name of template var.
     * @param mixed  $_mValue  Value of template var.
     *
     * @return ForwardFW_Templater_Smarty The instance.
     */
    public function setVar($_strName, $_mValue)
    {
        $this->smarty->assign($_strName, $_mValue);
        return $this;
    }

    /**
     * Returns compiled template for outputing.
     *
     * @return string Content of template after compiling.
     */
    public function getCompiled()
    {
        // Catch Exceptions and clear output cache
        try {
            $result = $this->smarty->fetch(
                $this->strTemplatePath . '/' . $this->strFile
            );
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return $result;
    }

    /**
     * Defines blocks in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function defineBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    /**
     * Shows block in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function showBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 1;
    }

    /**
     * Hide block in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function hideBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    /**
     * Block implementation for smarty.
     * Deprecated or should be used for template engines without conditions?
     */
    public function smartyBlock($params, $content, &$smarty, &$repeat)
    {
        $name = $params['name'];
        if (isset($this->arShowBlocks[$name]) && $this->arShowBlocks[$name] == 1) {
            return $content;
        }
        return '';
    }

    /**
     * Texter implementation for smarty.
     * More later, if it exists.
     */
    public function smartyTexter($params, &$smarty)
    {
        $strTextKey = $params['key'];

        $texter = ForwardFW_Texter::factory($this->strApplicationName);
        $result = $texter->getText($strTextKey);

        return $result;
    }
}
