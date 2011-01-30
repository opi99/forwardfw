<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);

ini_set('include_path', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . PATH_SEPARATOR . ini_get('include_path'));
ini_set('include_path', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libs' . PATH_SEPARATOR . ini_get('include_path'));

$GLOBALS['ForwardFW'] = array(
    'Version' => '0.0.10',
);

$GLOBALS['ForwardFW_Filter_RequestResponse'] = array(
    'ForwardFW_Filter_RequestResponse_FirePHP',
    'ForwardFW_Filter_RequestResponse_Application',
);

$GLOBALS['ForwardFW_Application']            = array(
    'class' => 'ForwardFW_Controller_Application',
    'name'  => 'ShortDemo',
);

$GLOBALS['ForwardFW_Templater']              = array(
    'Templater' => 'ForwardFW_Templater_Smarty',
#    'Templater' => 'ForwardFW_Templater_Twig',
);

$GLOBALS['ForwardFW_Templater_Smarty']       = array(
    'CompilePath'  => getcwd() . '/cache/',
    'TemplatePath' => getcwd() . '/data/templates/smarty'
);

$GLOBALS['ForwardFW_Templater_Twig']         = array(
    'CompilePath'  => getcwd() . '/cache/',
    'TemplatePath' => getcwd() . '/data/templates/twig'
);

$GLOBALS['ForwardFW_Controller_DataHandler']  = array(
    'default' => array(
        'handler' => 'ForwardFW_Controller_DataHandler_MDB2',
        'config' => array(
            'dsn' => 'mysql://john:doe@localhost/forwardfw',
            'options' => array(),
            'prefix'  => '',
        ),
    ),
);

$GLOBALS['ForwardFW_Controller_DataHandler_MDB2']  = array(
    'default' => array(
        'dsn' => 'mysql://john:doe@localhost/forwardfw',
        'options' => array(),
        'prefix'  => null,
    ),
);

$GLOBALS['ShortDemo']['screens']             = array(
    'Hello' => 'ForwardFW_Controller_Screen'
);

date_default_timezone_set('CET');
?>