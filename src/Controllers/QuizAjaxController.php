<?php

namespace Quiz\Controllers;


use Quiz\Models\QuestionModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Repositories\Database\AnswerDBRepository;
use Quiz\Repositories\Database\QuestionDBRepository;
use Quiz\Repositories\Database\QuizDBRepository;
use Quiz\Repositories\Database\UserAnswerDBRepository;
use Quiz\Repositories\Database\UserDBRepository;
use Quiz\Services\QuizService;
use Quiz\Services\QuizSessionService;

class QuizAjaxController extends BaseAjaxController
{
    public function getQuizzesAction()
    {
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
        //TODO: validator
        if (!isset($this->post['quizId'])) {
            return "";
        }
        $quizId = $this->post['quizId'];

        $session = QuizSessionService::getSession();
        $session->question = new QuestionModel();
        $session->quizId = $quizId;
        return $session->userId;
    }

    private function submitUserAnswer(int $answerId)
    {
        $session = QuizSessionService::getSession();

        $userAnswer = new UserAnswerModel;
        $userAnswer->userId = $session->userId;
        $userAnswer->quizId = $session->quizId;
        $userAnswer->questionId = $session->question->id;
        $userAnswer->answerId = $answerId;
        $this->quizService->submitAnswer($userAnswer);
    }

    public function submitAndLoadNextQuestionAction()
    {
        if (!isset($this->post['answerId'])) {
            return "no answer";
        }
        $session = QuizSessionService::getSession();
        $answerId = $this->post['answerId'];

        if($session->question->isValid()) {
            //validate
            $this->submitUserAnswer($answerId);
        }
        $nextQuestionNo = $session->question->questionNo + 1;
            $session->question = $this->quizService->getQuestionByNo($session->quizId, $nextQuestionNo);

        if ($session->question->isValid()) {
            //return question
            return $session->question;
        } else {
            //quiz is over
            $score = $this->quizService->getScore($session->userId, $session->quizId);
            return $score . "%";
        }
    }
}