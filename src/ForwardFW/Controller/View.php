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
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.2
 */

namespace ForwardFW\Controller;

/**
 * This class is a basic View class.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class View extends \ForwardFW\Controller
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\Application $application The running application.
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application)
    {
        parent::__construct($application);
        $this->strViewName = get_class($this);
    }

    /**
     * Processes the View.
     *
     * @return string
     */
    public function process()
    {
        $this->application->response->addLog('Processing ' . get_class($this));
        $this->controlView();
        return $this->processView();
    }

    /**
     * Control available data for View
     *
     * @return boolean True if all data exists.
     */
    public function controlView()
    {
        return true;
    }

    /**
     * Processes the View.
     *
     * @return void
     */
    public function processView()
    {
        $this->application->getResponse()->addLog(
            'Processing: ' . $this->getTemplateName() . '.tpl'
        );
        $templater = $this->application->getTemplater();
        $templater->setVar('ForwardFW_Version', $GLOBALS['ForwardFW']['Version']);
        try {
            $templater->setTemplateFile($this->getTemplateName() . '.tpl');
            return $templater->getCompiled();
        } catch (\Exception $e) {
            $this->application->response->addError($e->getMessage());
        }
    }

    /**
     * Returns the template name depending on the strViewName
     * It replaces the underscore with path_slashes.
     *
     * @return string Name of the template
     */
    protected function getTemplateName()
    {
        $strTemplateName = '';
//         $nLength = strlen($this->strViewName);
//         $nLastPart = strrpos($this->strViewName, '\\');
//         $nPreviewsPart = strrpos(
//             $this->strViewName,
//             '\\',
//             -($nLength - $nLastPart + 1)
//         );
//         if ($nPreviewsPart === false) {
//             $nPreviewsPart = -1;
//         }
//         $strTemplateName  = substr(
//             $this->strViewName,
//             $nPreviewsPart + 1,
//             $nLastPart - $nPreviewsPart - 1
//         );
//         $strTemplateName .= '/';
//         $strTemplateName .= substr($this->strViewName, $nLastPart + 1);
        $strTemplateName = strtr($this->strViewName, '\\', '/');
        return $strTemplateName;
    }
}
