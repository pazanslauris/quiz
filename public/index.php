<?php

require_once '../src/bootstrap.php';

use Quiz\Controllers\BaseController;
use Quiz\Repositories\Database\AnswerDBRepository;
use Quiz\Repositories\Database\QuestionDBRepository;
use Quiz\Repositories\Database\QuizDBRepository;
use Quiz\Repositories\Database\QuizResultDBRepository;
use Quiz\Repositories\Database\UserAnswerDBRepository;
use Quiz\Repositories\Database\UserDBRepository;
use Quiz\Services\QuizService;


$requestUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$requestString = substr($requestUrl, strlen($baseUrl));

$urlParams = explode('/', $requestString);
$controllerName = ucfirst(array_shift($urlParams));
$controllerName = $controllerNamespace . ($controllerName ? $controllerName : 'Index') . 'Controller';

$actionName = array_shift($urlParams);
$actionName = ($actionName ? $actionName : 'Index') . 'Action';

/** @var BaseController $controller */
$controller = new $controllerName(new QuizService(new QuizDBRepository(),
    new QuestionDBRepository(),
    new AnswerDBRepository(),
    new UserDBRepository(),
    new UserAnswerDBRepository(),
    new QuizResultDBRepository()));
$controller->handleCall($actionName);