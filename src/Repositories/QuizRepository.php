<?php

namespace Quiz\Repositories;

use Quiz\Models\QuizModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\AnswerModel;

class QuizRepository
{
    /** @var QuizModel[] */
    private $quizzes = [];
    /** @var QuestionModel[] */
    private $questions = [];
    /** @var AnswerModel[] */
    private $answers = [];


    /**
     * Add a quiz, does not validate.
     *
     * @param QuizModel $quiz
     * @return QuizModel
     */
    public function addQuiz(QuizModel $quiz): QuizModel
    {
        $this->quizzes[] = $quiz;
        return $quiz;
    }

    /**
     * Add a question, does not validate.
     *
     * @param QuestionModel $question
     * @return QuestionModel
     */
    public function addQuestion(QuestionModel $question): QuestionModel
    {
        $this->questions[] = $question;
        return $question;
    }

    /**
     * Add an answer, does not validate.
     *
     * @param AnswerModel $answer
     * @return AnswerModel
     */
    public function addAnswer(AnswerModel $answer): AnswerModel
    {
        $this->answers[] = $answer;
        return $answer;
    }


    /**
     * Gets all quizzes.
     *
     * @return QuizModel[]
     */
    public function getQuizzes(): array
    {
        return $this->quizzes;
    }

    /**
     * Gets a quiz by id.
     *
     * @param int $quizId
     * @return QuizModel
     */
    public function getQuiz(int $quizId): QuizModel
    {
        foreach ($this->quizzes as $quiz) {
            if ($quiz->id == $quizId) {
                return $quiz;
            }
        }
        return new QuizModel;
    }

    /**
     * Gets all questions that belong to a quiz.
     *
     * @param int $quizId
     * @return QuestionModel[]
     */
    public function getQuestions(int $quizId): array
    {
        $ret = [];
        foreach ($this->questions as $question) {
            if ($question->quizId == $quizId) {
                $ret[] = $question;
            }
        }
        return $ret;
    }

    /**
     * Gets a question by id.
     *
     * @param int $questionId
     * @return QuestionModel
     */
    public function getQuestion(int $questionId): QuestionModel
    {
        foreach ($this->questions as $question) {
            if ($question->id == $questionId) {
                return $question;
            }
        }
        return new QuestionModel;
    }

    /**
     * Gets all answers that belong to a question.
     *
     * @param int $questionId
     * @return AnswerModel[]
     */
    public function getAnswers(int $questionId): array
    {
        $ret = [];
        foreach ($this->answers as $answer) {
            if ($answer->questionId == $questionId) {
                $ret[] = $answer;
            }
        }
        return $ret;
    }

    /**
     * Gets an answer by id.
     *
     * @param int $answerId
     * @return AnswerModel
     */
    public function getAnswer(int $answerId): AnswerModel
    {
        foreach ($this->answers as $answer) {
            if ($answer->id == $answerId) {
                return $answer;
            }
        }
        return new AnswerModel;
    }
}