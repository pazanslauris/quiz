<?php

namespace Quiz\Controllers;


use Quiz\Repositories\Database\AnswerDBRepository;
use Quiz\Repositories\Database\QuestionDBRepository;
use Quiz\Repositories\Database\QuizDBRepository;
use Quiz\Repositories\Database\UserAnswerDBRepository;
use Quiz\Repositories\Database\UserDBRepository;
use Quiz\Services\QuizService;

class QuizAjaxController extends BaseAjaxController
{
    public function getQuizzesAction() {
        $service = new QuizService(new QuizDBRepository(),
            new QuestionDBRepository(),
            new AnswerDBRepository(),
            new UserDBRepository(),
            new UserAnswerDBRepository());

        $quizzes = $service->getQuizzes();
        return $quizzes;
    }

    public function startAction()
    {
        //validate input
        if (!isset($this->post['quizId'])) {
            return "";
        }

        $quizId = $this->post['quizId'];

        //start session
        session_start();
        $_SESSION['quizId'] = $quizId;
        $_SESSION['questionNo'] = 0;
        return $quizId;
    }

    public function loadNextQuestionAction()
    {
        //getSession()->current
        //get current question id
        //check if quiz is over
        //return next question by no
        return "nextQuestion";
    }
}