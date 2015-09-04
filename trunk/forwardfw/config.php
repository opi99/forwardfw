<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);

set_include_path(dirname(__FILE__) . '/src' . PATH_SEPARATOR . get_include_path());
set_include_path(dirname(__FILE__) . '/libs' . PATH_SEPARATOR . get_include_path());

require_once 'ForwardFW/Autoloader.php';

$GLOBALS['ForwardFW'] = array(
    'Version' => '0.1.1-dev',
);

$GLOBALS['ForwardFW\\Filter\\RequestResponse'] = array(
    (new ForwardFW\Config\Filter\RequestResponse\FirePhp()),
    (new ForwardFW\Config\Filter\RequestResponse\RegisterServices())
        ->addService(
            (new ForwardFW\Config\Service\DataHandler\Mdb2())
                ->setDsn('mysqli://john:doe@localhost/forwardfw')
                ->setTablePrefix('')
        ),
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

date_default_timezone_set('CET');
