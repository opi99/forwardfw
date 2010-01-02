<?php

require_once 'ForwardFW/Request.php';
require_once 'ForwardFW/Response.php';

require_once 'ForwardFW/Filter/RequestResponse.php';

$request = new ForwardFW_Request();
$response = new ForwardFW_Response();

ob_start();
ForwardFW_Filter_RequestResponse::getFilters($request, $response)
    ->doFilter();
ob_flush();
?>