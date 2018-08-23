<?php

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

    public function updateQuestion(QuestionModel $newQuestion): QuestionModel
    {
        foreach ($this->questions as $question) {
            if ($question->id == $newQuestion->id) {
                $question->question = $newQuestion->question;
                $question->questionNo = $newQuestion->questionNo;
                return $newQuestion;
            }
        }
        return new QuestionModel();
    }

    public function deleteQuestion(QuestionModel $oldQuestion): bool
    {
        foreach($this->questions as $key => $question) {
            if($question->id == $oldQuestion->id &&
                $question->quizId == $oldQuestion->quizId){
                unset($this->questions[$key]);
                return true;
            }
        }
        return false;
    }
}