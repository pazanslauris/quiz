<?php

namespace Quiz\Controllers;

use Quiz\Repositories\Database\AnswerDBRepository;
use Quiz\Repositories\Database\QuestionDBRepository;
use Quiz\Repositories\Database\QuizDBRepository;
use Quiz\Repositories\Database\UserAnswerDBRepository;
use Quiz\Repositories\Database\UserDBRepository;
use Quiz\Services\QuizService;
use Quiz\Services\QuizSessionService;

class AjaxController extends BaseAjaxController
{
    public function indexAction()
    {
        return 'hey';
    }

    public function saveUserAction()
    {
        if (!isset($this->post['name'])) {
            return 0;
        }
        $name = $this->post['name'];
        if (strlen($name) < 2) {
            return 0;
        }

        $service = new QuizService(new QuizDBRepository(),
            new QuestionDBRepository(),
            new AnswerDBRepository(),
            new UserDBRepository(),
            new UserAnswerDBRepository());

        $user = $service->registerUser($name);
        $session = QuizSessionService::getSession();
        $session->userId = $user->id;
        return $user;
    }


}