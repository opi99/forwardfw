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
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Templater
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.2
 */

/**
 *
 */
require_once 'ForwardFW/Controller.php';
require_once 'ForwardFW/Request.php';
require_once 'ForwardFW/Response.php';
require_once 'ForwardFW/Interface/Templater.php';

require_once 'Smarty/Smarty.class.php';

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
class ForwardFW_Templater_Smarty extends ForwardFW_Controller
    implements ForwardFW_Interface_Templater
{

    private $smarty = null;
    private $arShowBlocks = array();

    public function __construct(
        ForwardFW_Controller_Application $application
    ) {
        parent::__construct($application);

        $arConfig = $GLOBALS[get_class($this)];

        $strCompilePath = $arConfig['CompilePath'];
        if (!is_dir($strCompilePath)) {
            mkdir($strCompilePath, 0770, true);
        }

        $this->smarty = new Smarty();
        $this->smarty->compile_dir = $strCompilePath;
        $this->smarty->register_block('block', array(&$this, '__block'));
        $this->smarty->register_function('texter', array(&$this, '__texter'));

        $this->strTemplatePath = $arConfig['TemplatePath'];
    }

    public function setTemplateFile($_strFile)
    {
        $this->strFile = $_strFile;
    }

    public function setVar($_strName, $_mValue)
    {
        $this->smarty->assign($_strName, $_mValue);
    }

    public function getCompiled()
    {
        // Catch Exceptions and clear output cache
        try {
            $result = $this->smarty->fetch(
                $this->strTemplatePath . '/' . $this->strFile
            );
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return $result;
    }

    public function defineBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    public function showBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 1;
    }

    public function hideBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    public function __block($params, $content, &$smarty, &$repeat)
    {
        $name = $params['name'];
        if (isset($this->arShowBlocks[$name]) && $this->arShowBlocks[$name] == 1) {
            return $content;
        }
        return '';
    }

    public function __texter($params, &$smarty)
    {
        $strTextKey = $params['key'];

        $texter = ForwardFW_Texter::factory($this->strApplicationName);
        $result = $texter->getText($strTextKey);

        return $result;
    }
}

?>