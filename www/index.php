<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', true);
date_default_timezone_set('CET');

set_include_path(__DIR__ . '/../src' . PATH_SEPARATOR . get_include_path());

require_once 'ForwardFW/Bootstrap.php';

$bootstrap = new ForwardFW\Bootstrap();
$bootstrap->loadConfig(__DIR__ . '/../config.php');
$bootstrap->run();
