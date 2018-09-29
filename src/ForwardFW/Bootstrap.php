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
        $this->request = new Request();
        $this->response = new Response();
    }

    public function loadConfig(string $file): void
    {
        $this->config = require $file;

        if (!$this->config instanceof Config\Runner) {
            throw new \ForwardFW\Exception\BootstrapException('Config didn\'t return a runner configuration.');
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function run(): void
    {
        $class = $this->config->getExecutionClassName();
        $instance = new $class($this->config, $this->request, $this->response);
        $instance->run();
    }
}
