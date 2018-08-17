<?php

require_once '../src/bootstrap.php';

use Quiz\Controllers\BaseController;


$requestUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$requestString = substr($requestUrl, strlen($baseUrl));

$urlParams = explode('/', $requestString);
$controllerName = ucfirst(array_shift($urlParams));
$controllerName = $controllerNamespace . ($controllerName ? $controllerName : 'Index') . 'Controller';

$actionName = array_shift($urlParams);
$actionName = ($actionName ? $actionName : 'Index') . 'Action';

/** @var BaseController $controller */
$controller = new $controllerName;
$controller->handleCall($actionName);