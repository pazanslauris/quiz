<?php

namespace Quiz\Repositories\Database;

use Quiz\Interfaces\QuizRepositoryInterface;
use Quiz\Models\QuizModel;

class QuizDBRepository extends BaseDBRepository implements QuizRepositoryInterface
{
    /**
     * Add a quiz
     *
     * @param QuizModel $quizModel
     * @return QuizModel
     */
    public function addQuiz(QuizModel $quizModel): quizModel
    {
        $id = $this->insertRow(['name' => $quizModel->name]);
        $quizModel->id = $id;
        return $quizModel;
    }

    /**
     * Get all quizzes
     *
     * @return array
     */
    public function getQuizzes(): array
    {
        return $this->getAll(['id', 'name']);
    }

    /**
     * Get a quiz
     *
     * @param int $quizId
     * @return QuizModel
     */
    public function getQuiz(int $quizId): QuizModel
    {
        return $this->getById($quizId, ['id', 'name']);
    }


    public function getModelName(): string
    {
        return QuizModel::class;
    }

    public function getTableName(): string
    {
        return 'quizzes';
    }

    public function getPrimaryKey(): string
    {
        return 'id';
    }

    public function updateQuiz(QuizModel $quiz): QuizModel
    {
        //     * The primary key must be passed in the attributes... [ 'primary_key' => 1 ]
        $success = $this->updateColumn([ 'id' => $quiz->id, 'name' => $quiz->name]);
        if ($success) {
            return $quiz;
        }

        return new QuizModel;
    }

    public function deleteQuiz(QuizModel $quiz): bool
    {
        return $this->delete([ 'id' => $quiz->id]);
    }
}