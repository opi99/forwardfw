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

namespace ForwardFW\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ForwardFW\Factory\ResponseFactory;
use ForwardFW\Exception\NotFoundException;
use ForwardFW\Exception\RedirectException;

class ExceptionHandlingMiddleware extends \ForwardFW\Middleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (NotFoundException $e) {
            $response = (new ResponseFactory())
                ->createResponse(404, 'Not Found');
            $response->getBody()->write($e->getMessage());
        } catch (RedirectException $e) {
            $response = (new ResponseFactory())
                ->createResponse(301, 'Moved')
                ->withHeader('Location', $e->getLocation());
        } catch (\Throwable $e) {
            /** @var \Psr\Log\LoggerInterface */
            $logger = $this->serviceManager->getService(\Psr\Log\LoggerInterface::class);
            $logger->error('Error: ' . $e->getMessage());

            $response = (new ResponseFactory())
                ->createResponse(500, 'Not Found');
            //$response->getBody()->write('Internal Server Error');
        }
        
        return $response;
    }
}
