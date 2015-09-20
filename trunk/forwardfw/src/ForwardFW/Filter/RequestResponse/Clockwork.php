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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW\Filter\RequestResponse;

/**
 * This class sends the log and error message queue to the client via FirePHP.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Clockwork extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Enter Filter');

        $this->initClockwork();

        $path = $this->request->getRoutePath();

        if (preg_match('/__clockwork\/(.*)/', $path, $matches)) {
            header('Content-Type: application/json');
            echo $this->clockwork->getStorage()->retrieveAsJson($matches[1], $last);
            die();
        }
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Leave Filter');

        header('X-Clockwork-Id: ' . $this->clockwork->getRequest()->id);
        header('X-Clockwork-Version: ' . \Clockwork\Clockwork::VERSION);

        if ($this->request->getHostPath()) {
            header('X-Clockwork-Path: ' . $this->request->getHostPath() . '/__clockwork/');
        }

        $this->outputLog();
        $this->outputError();

        $this->clockwork->resolveRequest();
        $this->clockwork->storeRequest();
    }

    private function initClockwork()
    {
        $this->clockwork = (new \Clockwork\Clockwork())
            ->addDataSource(new \Clockwork\DataSource\PhpDataSource())
            ->setStorage(new \Clockwork\Storage\FileStorage('/tmp'));
    }

    /**
     * Adds the response log entries to the log group in FirePHP
     *
     * @return void
     */
    private function outputLog()
    {
        $entries = $this->response->getLogs()->getEntries();
        foreach ($entries as $message) {
            $this->clockwork->debug($message);
        }
    }


    /**
     * Adds the response error entries to the error group in FirePHP
     *
     * @return void
     */
    public function outputError()
    {
        $entries = $this->response->getErrors()->getEntries();
        foreach ($entries as $message) {
            $this->clockwork->error($message);
        }
    }
}
