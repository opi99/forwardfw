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
require_once 'ForwardFW/Controller.php';
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
class ForwardFW_Controller_Application extends ForwardFW_Controller
{
    /**
     * Configuration name of application
     *
     * @var String
     */
    protected $strApplicationName;

    /**
     * The request object.
     *
     * @var ForwardFW_Request
     */
    public $request;

    /**
     * The response object.
     *
     * @var ForwardFW_Response
     */
    public $response;

    /**
     * screens for this application
     *
     * @var array
     */
    private $arScreens = array();

    /**
     * Constructor
     *
     * @param string $_strApplicationName name of application
     *
     * @return void
     */
    public function __construct(
        $_strApplicationName,
        ForwardFW_Request $_request,
        ForwardFW_Response $_response
    ) {
        $this->strApplicationName = $_strApplicationName;
        $this->request            = $_request;
        $this->response           = $_response;

        parent::__construct($this);

        $this->arScreens = $this->getConfigParameter('screens');

        if (count($this->arScreens) === 0) {
            die(
                'No Screen defined for application: ' . $this->strApplicationName
            );
        }
    }

    /**
     * Run screen and return generated content
     *
     * @return string generated content form screens
     */
    function run()
    {
        $strProcessScreen = $this->getProcessScreen();

        try {
            $screen = $this->getScreen($strProcessScreen);
            if (!is_null($screen)) {
                $screen->process();
                // @TODO evaluate State of Screen

                // @TODO Nake List
                $screen->getViews()->process();
            }
        } catch (Exception $e) {
            // Logging
            throw $e;
        }
    }

    /**
     * Returns name of screen to be processed
     *
     * @return string name of screen to process
     */
    function getProcessScreen()
    {
        $strProcessScreen = reset(array_keys($this->arScreens));
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
            $this->response->addError('Screen Controller File "'.htmlspecialchars($strFile).'" not found');
        }
        return $screenController;
    }

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getApplicationName()
    {
        return $this->strApplicationName;
    }
}
?>