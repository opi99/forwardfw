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
 * @category   Request
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

class Bootstrap
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ForwardFW\Config\Runner
     */
    private $config;

    public function __construct()
    {
        require_once __DIR__ . '/Autoloader.php';
        $this->request = new Request();
        $this->response = new Response();
    }

    public function loadConfig($file)
    {
        $this->config = require_once $file;
        if (!$this->config instanceof \ForwardFW\Config\Runner) {
            throw new \Exception('Config didn\'t return a runner configuration.');
        }
    }

    public function run()
    {
        $class = $this->config->getExecutionClassName();
        $instance = new $class($this->config, $this->request, $this->response);
        $instance->run();
    }
}
