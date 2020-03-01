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
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.3
 */

namespace ForwardFW\Templater;

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
class Twig extends \ForwardFW\Controller implements \ForwardFW\Templater\TemplaterInterface
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

    /**
     * @var array Blocks to show
     */
    private $arShowBlocks = array();

    /**
     * Constructor
     *
     * @param ForwardFW\Config\Templater $config The twig configuration
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\Templater $config,
        \ForwardFW\Controller\ApplicationInterface $application
    ) {
        parent::__construct($application);

        $strCompilePath = $config->getCompilePath();

        if (!is_dir($strCompilePath)) {
            if (!@mkdir($strCompilePath, 0770, true)) {
                $error = error_get_last();
                throw new \Exception($error['message'] . "\n" . 'Path: ' . $strCompilePath);
            }
        }

        $twigLoader = new \Twig_Loader_Filesystem($config->getTemplatePath());
        $this->twigEnvironment = new \Twig_Environment(
            $twigLoader,
            array(
                'cache'      => $strCompilePath,
                'debug'      => true,
                'autoescape' => false,
            )
        );
    }

    /**
     * Sets file to use for templating
     *
     * @param string $strFile Complete path and filename.
     *
     * @return ForwardFW_Templater_Twig The instance.
     */
    public function setTemplateFile($strFile)
    {
        $this->twigTemplate = $this->twigEnvironment->loadTemplate(
            $strFile
        );
        return $this;
    }

    /**
     * Sets a var in the template to a value
     *
     * @param string $strName Name of template var.
     * @param mixed  $mValue  Value of template var.
     *
     * @return ForwardFW_Templater_Twig The instance.
     */
    public function setVar($strName, $mValue)
    {
        $this->arVars[$strName] = $mValue;
        return $this;
    }

    /**
     * Returns compiled template for outputing.
     *
     * @return string Content of template after compiling.
     */
    public function getCompiled()
    {
        $result = $this->twigTemplate->render(
            $this->arVars
        );
        return $result;
    }

    /**
     * @TODO Not combined with Twig
     */
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

    public function twigBlock($params, $content, &$smarty, &$repeat)
    {
        $name = $params['name'];
        if (isset($this->arShowBlocks[$name]) && $this->arShowBlocks[$name] == 1) {
            return $content;
        }
        return '';
    }

    public function twigTexter($params, &$smarty)
    {
        $strTextKey = $params['key'];

        $texter = ForwardFW_Texter::factory($this->strApplicationName);
        $result = $texter->getText($strTextKey);

        return $result;
    }
}
