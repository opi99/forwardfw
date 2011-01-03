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
 * @copyright  2009-2010 The Authors
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
require_once 'ForwardFW/Request.php';
require_once 'ForwardFW/Response.php';
//require_once 'ForwardFW/Exception/Screen.php';
//require_once 'ForwardFW/Exception/Application.php';

/**
 * This Controller over one application.
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Controller_Application extends ForwardFW_Controller_View
    implements ForwardFW_Interface_Application
{
    /**
     * Configuration name of application
     *
     * @var String
     */
    protected $strName;

    /**
     * The request object.
     *
     * @var ForwardFW_Request
     */
    protected $request;

    /**
     * The response object.
     *
     * @var ForwardFW_Response
     */
    protected $response;

    /**
     * screens for this application
     *
     * @var array
     */
    private $arScreens = array();

    /**
     * Constructor
     *
     * @param string             $strName  Name of application
     * @param ForwardFW_Request  $request  The request object.
     * @param ForwardFW_Response $response The request object.
     *
     * @return void
     */
    public function __construct(
        $strName,
        ForwardFW_Request $request,
        ForwardFW_Response $response
    ) {
        $this->strName = $strName;
        $this->request            = $request;
        $this->response           = $response;

        parent::__construct($this);

        $this->arScreens = $this->getConfigParameter('screens');

        if (count($this->arScreens) === 0) {
            die(
                'No Screen defined for application: ' . $this->strName
            );
        }
    }

    /**
     * Run screen and return generated content
     *
     * @return void
     */
    function run()
    {
        $strProcessScreen = $this->getProcessScreen();

        try {
            $this->screen = $this->getScreen($strProcessScreen);
            if (!is_null($this->screen)) {
                // @TODO evaluate State of Screen
                $strResult = $this->processView();
            }
        } catch (ForwardFW_Exception $e) {
            // Todo Inner Exception Logging
            throw $e;
        } catch (Exception $e) {
            // Todo Logging
            throw $e;
        }
        $this->response->addContent($strResult);
    }

    /**
     * Returns name of screen to be processed
     *
     * @return string name of screen to process
     */
    function getProcessScreen()
    {
        $strProcessScreen = $this->getParameter('screen');
        if (!isset($this->arScreens[$strProcessScreen])) {
            $strProcessScreen = reset(array_keys($this->arScreens));
        }
        return $strProcessScreen;
    }

    /**
     * Load and return screen $strScreen
     *
     * @param string $strScreen name of screen
     *
     * @return T3_Controller_Screen
     */
    function getScreen($strScreen)
    {
        $strFile = str_replace('_', '/', $this->arScreens[$strScreen]) . '.php';

        $rIncludeFile = @fopen($strFile, 'r', true);
        if ($rIncludeFile) {
            fclose($rIncludeFile);
            $ret = include_once $strFile;
            //Screen vorhanden?
            if (!$ret) {
                $this->response->addError('Screen not includeable.');
            } else {
                $screenController
                    = new $this->arScreens[$strScreen]($this);
            }
        } else {
            $this->response->addError(
                'Screen Controller File "'.htmlspecialchars($strFile).'" not found'
            );
        }
        return $screenController;
    }

    /**
     * Processes the View.
     *
     * @return string what to view
     */
    public function processView()
    {
        $templater = ForwardFW_Templater::factory($this->application);
        $templater->setVar('SCREEN', $this->screen->process());
        return parent::processView();
    }

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getName()
    {
        return $this->strName;
    }

    /**
     * Returns the request object
     *
     * @return ForwardFW_Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response object
     *
     * @return ForwardFW_Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the screen configuration for this application.
     *
     * @return array With the config entry
     */
    public function getScreens()
    {
        return $this->arScreens;
    }
}
?>