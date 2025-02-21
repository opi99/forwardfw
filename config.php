<?php

declare(strict_types=1);

return (new \ForwardFW\Config\Runner\HttpMiddlewareRunner())
/**
        ->addService(
            (new \ForwardFW\Config\Service\DataHandler\Mdb2())
                ->setDsn('mysqli://john:doe@localhost/forwardfw')
                ->setTablePrefix('')
        )*/
        // ->addService(
        //     (new \ForwardFW\Config\Service\Logger\ChromeLogger())
        // )
        ->addService(
            (new \ForwardFW\Config\Service\Logger\ClockworkLogger())
                ->enable()
        )
        // ->addMiddleware(
        //     new \ForwardFW\Config\Middleware\Logger\ChromeLogger()
        // )
        ->addMiddleware(
            (new \ForwardFW\Config\Middleware\Logger\ClockworkLogger())
        )
        ->addMiddleware(
            new \ForwardFW\Config\Middleware\SimpleRouter()
        )
        ->addMiddleware(
            (new \ForwardFW\Config\Middleware\Login\BasicAuth())
                ->setUsername('ao')
                ->setPassword('ao')
        )
        ->addMiddleware(
            (new \ForwardFW\Config\Middleware\Application())
                ->setConfig(
                    (new ForwardFW\Config\Application())
                        ->setName('ShortDemo')
                        ->setScreens(
                            [
                                'Hello' => \ForwardFW\Controller\Screen::class
                            ]
                        )
                        ->setTemplaterConfig(
                            (new ForwardFW\Config\Templater\Smarty())
                                ->setCompilePath(getcwd() . '/../cache/')
                                ->setTemplatePath(getcwd() . '/../data/templates/smarty')
                        )
                )
        );
