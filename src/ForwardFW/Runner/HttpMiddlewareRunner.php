<?php

declare(strict_types=1);

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

namespace ForwardFW\Runner;

use ForwardFW\Factory\ServerRequestFactory;
use ForwardFW\Middleware\MiddlewareIterator;
use ForwardFW\Middleware\MiddlewareIteratorTrait;
use ForwardFW\Runner;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpMiddlewareRunner
    extends Runner
    implements RequestHandlerInterface
{
    use MiddlewareIteratorTrait;

    private const MULTI_LINE_HEADERS = [
        'set-cookie',
    ];

    public function __construct(
        \ForwardFW\Config\Runner\HttpMiddlewareRunner $config
    ) {
        parent::__construct($config);
        $this->setMiddlewareIterator($this->config->getMiddlewares()->getIterator());
    }

    public function run()
    {
        $this->preRun();
        $this->outputResponse(
            $this->runMiddlewares()
        );
        $this->postRun();
    }

    protected function runMiddlewares(): ResponseInterface
    {
        $request = ServerRequestFactory::createFromGlobals();
        return $this->handle($request);
    }

    protected function outputResponse(ResponseInterface $response)
    {
        header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());

        foreach ($response->getHeaders() as $name => $values) {
            if (in_array(strtolower($name), self::MULTI_LINE_HEADERS, true)) {
                foreach ($values as $value) {
                    header($name . ': ' . $value, false);
                }
            } else {
                header($name . ': ' . (is_array($values) ? implode(', ', $values) : $values));
            }
        }

        $this->outputBody($response);
        flush();
        ob_flush();
    }

    private function outputBody(ResponseInterface $response): void
    {
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        echo $body->__toString();
    }
}
