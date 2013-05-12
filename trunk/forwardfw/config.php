<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);

set_include_path(dirname(__FILE__) . '/src' . PATH_SEPARATOR . get_include_path());
set_include_path(dirname(__FILE__) . '/libs' . PATH_SEPARATOR . get_include_path());

$GLOBALS['ForwardFW'] = array(
    'Version' => '0.0.11-dev',
);

$GLOBALS['ForwardFW\\Filter\\RequestResponse'] = array(
    'ForwardFW\\Filter\\RequestResponse\\FirePHP',
    'ForwardFW\\Filter\\RequestResponse\\Application',
);

$GLOBALS['ForwardFW\\Application'] = array(
    'class' => 'ForwardFW\\Controller\\Application',
    'name'  => 'ShortDemo',
);

$GLOBALS['ForwardFW\\Templater'] = array(
    'Templater' => 'ForwardFW\\Templater\\Smarty',
//     'Templater' => 'ForwardFW\\Templater\\Twig',
);

$GLOBALS['ForwardFW\\Templater\\Smarty'] = array(
    'CompilePath'  => getcwd() . '/../cache/',
    'TemplatePath' => getcwd() . '/../data/templates/smarty'
);

$GLOBALS['ForwardFW\\Templater\\Twig'] = array(
    'CompilePath'  => getcwd() . '/../cache/',
    'TemplatePath' => getcwd() . '/../data/templates/twig'
);

$GLOBALS['ForwardFW\\Controller\\DataHandler'] = array(
    'default' => array(
        'handler' => 'ForwardFW\\Controller\\DataHandler\\MDB2',
        'config' => array(
            'dsn' => 'mysqli://john:doe@localhost/forwardfw',
            'options' => array(),
            'prefix'  => '',
        ),
    ),
);

$GLOBALS['ForwardFW\\Controller\\DataHandler\\MDB2'] = array(
    'default' => array(
        'dsn' => 'mysqli://john:doe@localhost/forwardfw',
        'options' => array(),
        'prefix'  => null,
    ),
);

$GLOBALS['ShortDemo']['screens'] = array(
    'Hello' => 'ForwardFW\\Controller\\Screen'
);

date_default_timezone_set('CET');
