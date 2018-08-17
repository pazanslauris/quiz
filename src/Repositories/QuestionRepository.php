<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/16/2018
 * Time: 3:30 PM
 */

namespace Quiz\Repositories;


use Quiz\Interfaces\QuestionRepositoryInterface;
use Quiz\Models\QuestionModel;

class QuestionRepository implements QuestionRepositoryInterface
{
    /** @var QuestionModel[] */
    private $questions = [];

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

    public function getQuestionByNo(int $quizId, int $questionNo): QuestionModel
    {
        foreach ($this->questions as $question) {
            if ($question->quizId == $quizId &&
                $question->questionNo == $questionNo) {
                return $question;
            }
        }
        return new QuestionModel();
    }
}