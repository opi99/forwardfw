<?php

declare(strict_types=1);

return (new \ForwardFW\Config\Runner\HttpMiddlewareRunner())
        // Add Mezzio authentication config into PSR-11 container
        ->addContainerVar(
            'config',
            [
                'authentication' => [
                    'realm' => 'Password required',
                    'pdo' => [
                        'table' => 'user',
                        'field' => [
                            'identity' => 'identity',
                            'password' => 'password',
                        ],
                        //'service' => 'PdoService' DataHandler can't do this ATM ... and he realy needs a rewrite
                        'dsn' => 'mysql:host=db;port=3306;dbname=db',
                        'username' => 'db',
                        'password' => 'db',
                    ],
                ],
            ]
        )
        // Register our factory in PSR-11 Container
        ->addService(
          (new ForwardFW\Config\Service())
            ->setInterfaceName(\Psr\Http\Message\ResponseFactoryInterface::class)
            ->setExecutionClassName(\ForwardFW\Factory\ResponseFactory::class)
        )
        // Configure Mezzio PdoDatabase as UserRepository
        ->addService(
          (new ForwardFW\Config\Service())
            ->setInterfaceName(\Mezzio\Authentication\UserRepositoryInterface::class)
            ->setExecutionClassName(\Mezzio\Authentication\UserRepository\PdoDatabase::class)
            ->setFactoryFunction(\Mezzio\Authentication\UserRepository\PdoDatabaseFactory::class)
        )
        // Configure Mezzio DefaultUser as UserInterface
        ->addService(
          (new ForwardFW\Config\Service())
            ->setInterfaceName(\Mezzio\Authentication\UserInterface::class)
            ->setExecutionClassName(\Mezzio\Authentication\DefaultUser::class)
            ->setFactoryFunction(\Mezzio\Authentication\DefaultUserFactory::class)
        )
        // Configure Mezzio BasicAccess as AuthenticationInterface
        ->addService(
          (new ForwardFW\Config\Service())
            ->setInterfaceName(\Mezzio\Authentication\AuthenticationInterface::class)
            ->setExecutionClassName(\Mezzio\Authentication\Basic\BasicAccess::class)
            ->setFactoryFunction(\Mezzio\Authentication\Basic\BasicAccessFactory::class)
        )
        ->addService(
            (new \ForwardFW\Config\Service\Logger\Manager())
                // ->addLoggerService(new \ForwardFW\Config\Service\Logger\ChromeLogger())
                ->addLoggerService(
                    (new \ForwardFW\Config\Service\Logger\ClockworkLogger())
                    ->enable()
                )
        )
        // Add the Mezzio Authentication Middleware
        ->addMiddleware(
            new \ForwardFW\Config\Middleware()
                ->setExecutionClassName(\Mezzio\Authentication\AuthenticationMiddlewareFactory::class)
                ->setFactoryFunction(\Mezzio\Authentication\AuthenticationMiddlewareFactory::class)
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
                                ->addExtensionClass(\Twig\Extension\DebugExtension::class)
                        )
                )
        );
