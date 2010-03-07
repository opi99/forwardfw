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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

/**
 *
 */
require_once 'ForwardFW/Controller/View.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'ForwardFW/Interface/Screen.php';

/**
 * This class is a basic Screen class.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Controller_Screen extends ForwardFW_Controller_View
    implements ForwardFW_Interface_Screen
{
    /**
     * The View which should be used.
     *
     * @var ForwardFW_Controller_View
     */
    private $view;

    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application $_application The running application.
     *
     * @return void
     */
    public function __construct(ForwardFW_Interface_Application $_application)
    {
        parent::__construct($_application);
        $this->strView = 'ForwardFW_Controller_View';
    }

    /**
     * Processes the Screen.
     *
     * @return void
     */
    public function process()
    {
        $this->application->getResponse()->addLog('Processing ' . get_class($this));
        $this->controlInput();
        $this->processInput();
        $this->controlView();
        $this->processView();
    }

    /**
     * Control the user input, if available.
     *
     * @return boolean True if all user input was accepted.
     */
    public function controlInput()
    {
        return true;
    }


    /**
     * Do some processing with user Input.
     *
     * @return boolean True if processing was succesfully.
     */
    public function processInput()
    {
        return true;
    }

    /**
     * Loads Data for views and defines which views to use.
     * strView is used.
     *
     * @return boolean True if screen wants to be viewed. Necessary for MultiApps.
     */
    public function controlView()
    {
        $strFile = str_replace('_', '/', $this->strView) . '.php';
        include_once $strFile;
        $view = new $this->strView($this->application);
        $this->addView($view);
        parent::controlView();
        return true;
    }

    /**
     * Processes the View.
     *
     * @return void
     */
    public function processView()
    {
        $templater = ForwardFW_Templater::factory($this->application);
        $templater->setVar('VIEW', $this->getViews()->process());
        echo parent::processView();
    }

    /**
     * Adds a view to the list of views.
     *
     * @param ForwardFW_Controller_View $_view The view to add.
     *
     * @return ForwardFW_Controller_Screen This Screen.
     *
     * @TODO Implement as List.
     */
    protected function addView(ForwardFW_Controller_View $_view)
    {
        $this->view = $_view;
        return $this;
    }


    /**
     * Returns the list of views to show.
     *
     * @return ForwardFW_Controller_View The list of views.
     *
     * @TODO Implement as List.
     */
    public function getViews()
    {
        return $this->view;
    }
}
?>