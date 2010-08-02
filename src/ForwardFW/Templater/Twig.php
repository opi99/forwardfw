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
 * @copyright  2009-2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.3
 */

/**
 *
 */
require_once 'ForwardFW/Controller.php';
require_once 'ForwardFW/Request.php';
require_once 'ForwardFW/Response.php';
require_once 'ForwardFW/Interface/Templater.php';

require_once 'Twig/Autoloader.php';

/**
 * Class to use Twig as Templater.
 *
 * @category   Templater
 * @package    ForwardFW
 * @subpackage Templater
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Templater_Twig extends ForwardFW_Controller
    implements ForwardFW_Interface_Templater
{
    /**
     * @var Twig_Environment The Twig instance
     */
    private $twigEnvironment = null;

    /**
     * @var Twig_Template The loaded template
     */
    private $twigTemplate = null;

    /**
     * @var array The array of vars for the template
     */
    private $arVars = array();

    private $arShowBlocks = array();

    /**
     * Constructor
     *
     * @param ForwardFW_Controller_Application $application The running application
     *
     * @return void
     */
    public function __construct(
        ForwardFW_Controller_Application $application
    ) {
        parent::__construct($application);

        Twig_Autoloader::register();

        $arConfig = $GLOBALS[get_class($this)];

        $strCompilePath = $arConfig['CompilePath'];
        if (!is_dir($strCompilePath)) {
            mkdir($strCompilePath, 0770, true);
        }

        $twigLoader = new Twig_Loader_Filesystem($arConfig['TemplatePath']);
        $this->twigEnvironment = new Twig_Environment($twigLoader,
            array(
                'cache' => $strCompilePath
            )
        );
    }

    public function setTemplateFile($_strFile)
    {
        $this->twigTemplate = $this->twigEnvironment->loadTemplate(
            $_strFile
        );
    }

    public function setVar($_strName, $_mValue)
    {
        $this->arVars[$_strName] = $_mValue;
    }

    public function getCompiled()
    {
        $result = $this->twigTemplate->render(
            $this->arVars
        );
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