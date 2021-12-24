<?php

declare(strict_types=1);

return (new \ForwardFW\Config\Runner())
/**
        ->addService(
            (new \ForwardFW\Config\Service\DataHandler\Mdb2())
                ->setDsn('mysqli://john:doe@localhost/forwardfw')
                ->setTablePrefix('')
        )*/
        ->addMiddleware(
            new \ForwardFW\Config\Middleware\ChromeLogger()
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
