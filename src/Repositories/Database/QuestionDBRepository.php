<?php

namespace Quiz\Repositories\Database;


use Quiz\Interfaces\QuestionRepositoryInterface;
use Quiz\Models\QuestionModel;

class QuestionDBRepository extends BaseDBRepository implements QuestionRepositoryInterface
{
    /**
     * Add a question
     *
     * @param QuestionModel $question
     * @return QuestionModel
     */
    public function addQuestion(QuestionModel $question): QuestionModel
    {
        $id = $this->insertRow(['question' => $question->question,
            'quiz_id' => $question->quizId,
            'question_no' => $question->questionNo]);
        $question->id = $id;
        return $question;
    }

    /**
     * Gets all questions that are associated with a given quiz
     *
     * @param int $quizId
     * @return QuestionModel[]
     */
    public function getQuestions(int $quizId): array
    {
        return $this->getByConditions(['quiz_id' => $quizId], ['id', 'question', 'quiz_id', 'question_no']);
    }

    /**
     * Gets a single question
     *
     * @param int $questionId
     * @return QuestionModel
     */
    public function getQuestion(int $questionId): QuestionModel
    {
        return $this->getById($questionId, ['id', 'question', 'quiz_id', 'question_no']);
    }

    /**
     * Get question by num
     *
     * @param int $quizId
     * @param int $questionNo
     * @return QuestionModel
     */
    public function getQuestionByNo(int $quizId, int $questionNo): QuestionModel
    {
        $question = $this->getByConditions(['quiz_id' => $quizId, 'question_no' => $questionNo],
            ['id', 'question', 'quiz_id', 'question_no']);
        return array_shift($question);
    }

    public function getModelName(): string
    {
        return QuestionModel::class;
    }

    public function getTableName(): string
    {
        return 'quiz_questions';
    }

    public function getPrimaryKey(): string
    {
        return 'id';
    }
}