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

declare(strict_types=1);

namespace ForwardFW\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * This class sends the log and error message queue to the client via ChromeLogger.
 */
class ChromeLogger extends \ForwardFW\Middleware
{
    protected $chromeLogger = null;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->response->addLog('Enter Filter');

        $response = $handler->handle($request);

        $this->response->addLog('Leave Filter');
        $this->chromeLogger = new \Kodus\Logging\ChromeLogger();

        $this->outputLog();
        $this->outputError();

        $this->chromeLogger->emitHeader();

        return $response;
    }

    /**
     * Adds the response log entries to the log group in FirePHP
     *
     * @return void
     */
    private function outputLog()
    {
        $arLogs = $this->response->getLogs()->getEntries();
        foreach ($arLogs as $strMessage) {
            $this->chromeLogger->notice($strMessage);
        }
    }


    /**
     * Adds the response error entries to the error group in FirePHP
     *
     * @return void
     */
    public function outputError()
    {
        $arErrors = $this->response->getErrors()->getEntries();
        foreach ($arErrors as $strMessage) {
            $this->chromeLogger->error($strMessage);
        }
    }
}
