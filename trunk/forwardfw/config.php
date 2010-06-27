<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);

ini_set('include_path', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . PATH_SEPARATOR . ini_get('include_path'));
ini_set('include_path', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libs' . PATH_SEPARATOR . ini_get('include_path'));

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

$GLOBALS['ForwardFW_Controller_DataLoader']  = array(
    'default' => array(
        'type' => 'MDB2',
        'config' => array(
            'dsn' => 'mysql://root@localhost/forwardfw',
            'options' => array(),
            'prefix'  => '',
        ),
    ),
);

$GLOBALS['ShortDemo']['screens']             = array(
    'Hello' => 'ForwardFW_Controller_Screen'
);

date_default_timezone_set('CET');
?>