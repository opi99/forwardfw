<?php

declare(strict_types=1);

return (new \ForwardFW\Config\Runner())
/**
        ->addService(
            (new \ForwardFW\Config\Service\DataHandler\Mdb2())
                ->setDsn('mysqli://john:doe@localhost/forwardfw')
                ->setTablePrefix('')
        )*/
        ->addService(
            (new \ForwardFW\Config\Service\Logger\ChromeLogger())
        )
        ->addMiddleware(
            new \ForwardFW\Config\Middleware\ChromeLogger()
        )
        ->addMiddleware(
            new \ForwardFW\Config\Middleware\SimpleRouter()
        );
/**
        ->addProcessor(
            (new ForwardFW\Config\Filter\RequestResponse\Application())
                ->setConfig(
                    (new ForwardFW\Config\Application())
                        ->setName('ShortDemo')
                        ->setScreens(
                            array(
                                'Hello' => 'ForwardFW\\Controller\\Screen'
                            )
                        )
                        ->setTemplaterConfig(
                            (new ForwardFW\Config\Templater\Smarty())
                                ->setCompilePath(getcwd() . '/../cache/')
                                ->setTemplatePath(getcwd() . '/../data/templates/smarty')
                        )
                )
        );
*/
