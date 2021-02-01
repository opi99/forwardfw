<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW\Filter\RequestResponse;

/**
 * This class sends the log and error message queue to the client via FirePHP.
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
