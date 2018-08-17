<?php

namespace Quiz\Controllers;


use Quiz\Services\QuizSessionService;

class IndexController extends BaseController
{
    public function IndexAction()
    {
        $session = QuizSessionService::getSession();
        $user = $this->quizService->getUser($session->userId);
        if ($user->isValid()) {
            return $this->render('index', compact('user'));
        } else {
            //User is not logged in
            return $this->render('login');
        }
    }

    public function initUserAction() {
        if (!isset($_POST['name'])) {
            header('Location: \\');
            die();
        }
        $name = $_POST['name'];
        $user = $this->quizService->registerUser($name);

        $session = QuizSessionService::getSession();
        $session->userId = $user->id;
        header('Location: \\');
    }
}