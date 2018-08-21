<?php

namespace Quiz\Controllers;


use Quiz\Services\QuizSessionService;

class IndexController extends BaseController
{
    public function IndexAction()
    {
        return $this->render('index');
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
        die();
    }
}