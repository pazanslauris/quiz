<?php

namespace Quiz\Controllers;


use Quiz\Models\AnswerModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Models\ResponseModel;
use Quiz\Services\QuizSessionService;

class AdminAjaxController extends BaseAjaxController
{
    private function isUserAuthorized(): bool
    {
        $session = QuizSessionService::getSession();
        return $session->isAdmin;
    }

    public function getQuestionsAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['quizId'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No quizid specified');
        }

        $quizId = $this->post['quizId'];
        $questions = $this->quizService->getQuestions($quizId);

        $responseArray = [];
        foreach ($questions as $question) {
            $answers = $this->quizService->getAnswers($question->id);

            $responseArray[] = [
                'question' => $question,
                'answers' => $answers
            ];
        }

        return new ResponseModel(ResponseModel::QUESTIONS, $responseArray);
    }

    public function updateQuizAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['quiz'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No quiz given');
        }

        if ($this->post['quiz']['id'] == 0) {
            //if id is 0 then redirect to add a new quiz
            return $this->addQuizAction();
        }

        $quizArray = $this->post['quiz'];
        $updatedQuiz = new QuizModel;
        $updatedQuiz->id = $quizArray['id'];
        $updatedQuiz->name = $quizArray['name'];

        $quiz = $this->quizService->updateQuiz($updatedQuiz);
        $quiz->totalQuestionCount = $quizArray['totalQuestionCount'];
        return new ResponseModel(ResponseModel::QUIZ, $quiz);
    }

    public function updateQuestionAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['question'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No question given');
        }

        if ($this->post['question']['id'] == 0) {
            //if id is 0 then redirect to add a new quiz
            return $this->addQuestionAction();
        }

        $questionArray = $this->post['question'];
        $updatedQuestion = new QuestionModel;
        $updatedQuestion->id = $questionArray['id'];
        $updatedQuestion->question = $questionArray['question'];
        $updatedQuestion->questionNo = $questionArray['questionNo'];

        $question = $this->quizService->updateQuestion($updatedQuestion);
        $answers = $this->quizService->getAnswers($question->id);
        $response = [
            'question' => $question,
            'answers' => $answers,
        ];
        return new ResponseModel(ResponseModel::QUESTION, $response);
    }

    public function updateAnswerAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['answer'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No answer given');
        }

        if ($this->post['answer']['id'] == 0) {
            //if id is 0 then redirect to add a new quiz
            return $this->addAnswerAction();
        }

        $answerArray = $this->post['answer'];
        $updatedAnswer = new AnswerModel;
        $updatedAnswer->id = $answerArray['id'];
        $updatedAnswer->answer = $answerArray['answer'];
        $updatedAnswer->isCorrect = $answerArray['isCorrect'];

        $answer = $this->quizService->updateAnswer($updatedAnswer);
        return new ResponseModel(ResponseModel::ANSWER, $answer);
    }

    public function addQuizAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['quiz'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No quiz given');
        }

        $quizArray = $this->post['quiz'];
        $newQuiz = new QuizModel;
        $newQuiz->name = $quizArray['name'];

        $quiz = $this->quizService->addQuiz($newQuiz);
        return new ResponseModel(ResponseModel::QUIZ, $quiz);
    }

    public function addQuestionAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['question'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No question given');
        }

        $questionArray = $this->post['question'];
        $newQuestion = new QuestionModel;
        $newQuestion->question = $questionArray['question'];
        $newQuestion->questionNo = $questionArray['questionNo'];
        $newQuestion->quizId = $questionArray['quizId'];

        $question = $this->quizService->addQuestion($newQuestion);
        $response = [
            'question' => $question,
            'answers' => [],
        ];
        return new ResponseModel(ResponseModel::QUESTION, $response);
    }

    public function addAnswerAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['answer'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No answer given');
        }

        $answerArray = $this->post['answer'];
        $newAnswer = new AnswerModel;
        $newAnswer->id = $answerArray['id'];
        $newAnswer->answer = $answerArray['answer'];
        $newAnswer->isCorrect = $answerArray['isCorrect'];
        $newAnswer->questionId = $answerArray['questionId'];

        $answer = $this->quizService->addAnswer($newAnswer);
        return new ResponseModel(ResponseModel::ANSWER, $answer);
    }

    public function deleteQuizAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['quiz'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No quiz given');
        }

        $quizArray = $this->post['quiz'];
        $deleteQuiz = new QuizModel;
        $deleteQuiz->id = $quizArray['id'];

        $status = $this->quizService->deleteQuiz($deleteQuiz);
        return new ResponseModel(ResponseModel::STATUS, $status);
    }

    public function deleteQuestionAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['question'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No question given');
        }

        $questionArray = $this->post['question'];
        $deleteQuestion = new QuestionModel;
        $deleteQuestion->id = $questionArray['id'];
        $deleteQuestion->quizId = $questionArray['quizId'];

        $status = $this->quizService->deleteQuestion($deleteQuestion);
        return new ResponseModel(ResponseModel::STATUS, $status);
    }

    public function deleteAnswerAction()
    {
        if (!$this->isUserAuthorized()) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'Permission denied');
        }

        if (!isset($this->post['answer'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, 'No answer given');
        }

        $answerArray = $this->post['answer'];
        $deleteAnswer = new AnswerModel;
        $deleteAnswer->id = $answerArray['id'];
        $deleteAnswer->questionId = $answerArray['questionId'];

        $status = $this->quizService->deleteAnswer($deleteAnswer);
        return new ResponseModel(ResponseModel::STATUS, $status);
    }
}