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
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW\Controller;

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
class Application extends ApplicationAbstract
{
    /**
     * screens for this application
     *
     * @var array
     */
    private $arScreens = array();

    /**
     * Actuall screen to process.
     *
     * @var ForwardFW\Controller\ScreenInterface
     */
    private $screen = null;

    private $templater;

    /**
     * Constructor
     *
     * @param ForwardFW\Config\Application $config   Name of application.
     * @param ForwardFW\Request            $request  The request object.
     * @param ForwardFW\Response           $response The request object.
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\Application $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response
    ) {
        parent::__construct($config, $request, $response);

        $this->arScreens = $this->config->getScreens();

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
    public function run()
    {
        $strProcessScreen = $this->getProcessScreen();

        try {
            $this->screen = $this->getScreen($strProcessScreen);
            if (!is_null($this->screen)) {
                // @TODO evaluate State of Screen
                $strResult = $this->processView();
            }
        } catch (\ForwardFW\Exception $e) {
            // Todo Inner Exception Logging
            throw $e;
        } catch (\Exception $e) {
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
    public function getProcessScreen()
    {
        $strProcessScreen = $this->getParameter('screen');
        if (!isset($this->arScreens[$strProcessScreen])) {
            $strProcessScreen = array_keys($this->arScreens)[0];
        }
        return $strProcessScreen;
    }

    /**
     * Load and return screen $strScreen
     *
     * @param string $strScreen name of screen
     *
     * @return ForwardFW\Controller\ScreenInterface
     */
    public function getScreen($strScreen)
    {
        $strScreenClass = $this->arScreens[$strScreen];

        if (class_exists($strScreenClass)) {
            $screenController = new $strScreenClass($this);
        } else {
            $this->response->addError('ScreenClass "' . $strScreenClass . '" not includeable.');
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
        $templater = $this->getTemplater();
        $templater->setVar('APPLICATION', $this);
        $templater->setVar('SCREEN', $this->screen->process());
        return parent::processView();
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

    public function getTemplater()
    {
        if (null === $this->templater) {
            $this->templater = \ForwardFW\Templater::factory($this->config->getTemplaterConfig(), $this);
        }
        return $this->templater;
    }
}
