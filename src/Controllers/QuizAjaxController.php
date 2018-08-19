<?php

namespace Quiz\Controllers;

use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Services\QuizSessionService;

class QuizAjaxController extends BaseAjaxController
{
    /**
     * Returns all available(uncompleted) quizzes.
     *
     * @return array|QuizModel[]
     */
    public function getQuizzesAction()
    {
        $session = QuizSessionService::getSession();

        $quizzes = $this->quizService->getAvailableQuizzes($session->userId);
        return $quizzes;
    }

    /**
     * Starts a new quiz
     *
     * @return int|string
     */
    public function startQuizAction()
    {
        $session = QuizSessionService::getSession();
        if ($session->userId == 0) {
            return false;
        }

        //TODO: validator
        if (!isset($this->post['quizId'])) {
            return false;
        }
        $quizId = $this->post['quizId'];

        //Check if quiz is already completed
        if ($this->quizService->isQuizCompleted($session->userId, $quizId)) {
            return false;
        }

        $session->question = new QuestionModel();
        $session->quizId = $quizId;
        return true;
    }

    /**
     * Submits a user answer using the session variables
     *
     * @param int $answerId
     * @return UserAnswerModel
     */
    private function submitUserAnswer(int $answerId): UserAnswerModel
    {
        $session = QuizSessionService::getSession();

        $userAnswer = new UserAnswerModel;
        $userAnswer->userId = $session->userId;
        $userAnswer->quizId = $session->quizId;
        $userAnswer->questionId = $session->question->id;
        $userAnswer->answerId = $answerId;
        return $this->quizService->submitAnswer($userAnswer);
    }

    /**
     * Returns the next question or score if the quiz is complete.
     *
     * @return QuestionModel|string
     */
    public function loadNextQuestionAction()
    {
        $session = QuizSessionService::getSession();
        $session->question = $this->quizService->getNextQuestion($session->userId, $session->quizId);

        if ($session->question->isValid()) {
            //return question
            return $session->question;
        } else {
            //quiz is over
            $this->quizService->submitResult($session->userId, $session->quizId);
            $score = $this->quizService->getScore($session->userId, $session->quizId);
            return $score . "%";
        }
    }

    /**
     * Submits a user answer
     *
     * @return bool
     */
    public function submitAnswerAction()
    {
        if (!isset($this->post['answerId'])) {
            return false;
        }
        $session = QuizSessionService::getSession();
        $answerId = $this->post['answerId'];

        if ($session->question->isValid()) {
            $userAnswer = $this->submitUserAnswer($answerId);
            return $userAnswer->isValid();
        }
        return false;
    }

    /**
     * Submits a user answer and loads the next question.
     *
     * @return QuestionModel|string
     */
    public function submitAndLoadNextQuestionAction()
    {
        //Submit an answer if one is supplied...
        $this->submitAnswerAction();

        //Load the next question...
        return $this->loadNextQuestionAction();
    }
}