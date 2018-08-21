<?php

namespace Quiz\Controllers;

use Quiz\Models\QuestionModel;
use Quiz\Models\ResponseModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Services\QuizSessionService;

class QuizAjaxController extends BaseAjaxController
{
    /**
     * Returns all available(uncompleted) quizzes.
     *
     * @return ResponseModel
     */
    public function getQuizzesAction()
    {
        //$session = QuizSessionService::getSession();
        //$quizzes = $this->quizService->getAvailableQuizzes($session->userId);

        $quizzes = $this->quizService->getAllQuizzes();
        $response = new ResponseModel(ResponseModel::QUIZZES, $quizzes);
        return $response;
    }

    /**
     * Starts a new quiz
     *
     * @return ResponseModel
     */
    public function startQuizAction()
    {
        $session = QuizSessionService::getSession();

        //TODO: validator
        if (!isset($this->post['quizId'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, "no quiz id");
        }
        $quizId = $this->post['quizId'];
        if ($session->userId == 0) {
            return new ResponseModel(ResponseModel::ERRORMSG, "not logged in");

            //register & start quiz
//            if (!isset($this->post['name'])) {
//                return new ResponseModel(ResponseModel::ERRORMSG, "no name");
//            }
//            $session->userId = $this->quizService->registerUser($this->post['name'])->id;
        }


        //Check if quiz is already completed
        if ($this->quizService->isQuizCompleted($session->userId, $quizId)) {
            //return new ResponseModel( ResponseModel::ERRORMSG, "Quiz is already completed");
            return $this->getResultAction();
        }

        $session->question = new QuestionModel();
        $session->quizId = $quizId;

        //Return the 1st(or resume from another) question
        return $this->loadNextQuestionAction();
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
     * @return ResponseModel
     */
    public function loadNextQuestionAction()
    {
        $session = QuizSessionService::getSession();
        $session->question = $this->quizService->getNextQuestion($session->userId, $session->quizId);

        if ($session->question->isValid()) {
            $answers = $this->quizService->getAnswers($session->question->id);
            $response = new ResponseModel(ResponseModel::QUESTION, [
                'question' => $session->question,
                'answers' => $answers
            ]);
            return $response;
        } else {
            //quiz is over
            $this->quizService->submitResult($session->userId, $session->quizId);
            return $this->getResultAction();
        }
    }

    /**
     * Submits a user answer
     *
     * @return ResponseModel
     */
    public function submitAnswerAction()
    {
        if (!isset($this->post['answerId'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, "answerId wasn't set");
        }
        $session = QuizSessionService::getSession();
        $answerId = $this->post['answerId'];

        if ($session->question->isValid()) {
            $userAnswer = $this->submitUserAnswer($answerId);
            $status = $userAnswer->isValid();
            return new ResponseModel(ResponseModel::STATUS, $status);
        }
        return new ResponseModel(ResponseModel::ERRORMSG, "current question is invalid");
    }

    /**
     * Submits a user answer and loads the next question.
     *
     * @return ResponseModel
     */
    public function submitAndLoadNextQuestionAction()
    {
        //Submit an answer if one is supplied...
        $this->submitAnswerAction();

        //Load the next question...
        return $this->loadNextQuestionAction();
    }

    /**
     * Returns the result of a quiz(defaults to the current selected quiz).
     *
     * @return ResponseModel
     */
    public function getResultAction()
    {
        $session = QuizSessionService::getSession();

        if (isset($this->post['quizId'])) {
            $quizId = $this->post['quizId'];
        } else {
            $quizId = $session->quizId;
        }

        $result = $this->quizService->getResult($session->userId, $quizId);

        $response = new ResponseModel(ResponseModel::RESULT, $result);
        return $response;
    }

    /**
     * Logs a user out...
     *
     * @return ResponseModel
     */
    public function logoutAction()
    {
        QuizSessionService::endSession();
        return new ResponseModel(ResponseModel::STATUS, true);
    }
}