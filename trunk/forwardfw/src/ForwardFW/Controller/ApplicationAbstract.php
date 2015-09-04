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
 * @since      File available since Release 0.0.11
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
abstract class ApplicationAbstract extends View implements ApplicationInterface
{
    /**
     * The request object.
     *
     * @var \ForwardFW\Request
     */
    protected $request;

    /**
     * The response object.
     *
     * @var \ForwardFW\Response
     */
    protected $response;

    /**
     * @var ForwardFW\ServiceManager The ServiceManager object
     */
    protected $serviceManager = null;

    /**
     * @var \ForwardFW\Config\Application Configuration
     */
    protected $config = null;

    /**
     * Constructor
     *
     * @param \ForwardFW\Config\Application $config         Name of application.
     * @param \ForwardFW\Request            $request        The request object.
     * @param \ForwardFW\Response           $response       The request object.
     * @param \ForwardFW\Service            $serviceManager The services for this application
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\Application $config,
        \ForwardFW\Request $request,
        \ForwardFW\Response $response,
        \ForwardFW\ServiceManager $serviceManager
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
        $this->serviceManager = $serviceManager;

        parent::__construct($this);
    }

    /**
     * Run screen and return generated content
     *
     * @return void
     */
    abstract public function run();

    /**
     * Returns the name of the application
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * Returns the ident of the application
     *
     * @return string
     */
    public function getIdent()
    {
        return $this->config->getIdent();
    }

    /**
     * Returns the request object
     *
     * @return \ForwardFW\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response object
     *
     * @return \ForwardFW\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the response object of this process
     *
     * @return \ForwardFW\Response
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
