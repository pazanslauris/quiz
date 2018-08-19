<?php

namespace Quiz\Repositories\Database;


use Quiz\Interfaces\QuizResultRepositoryInterface;
use Quiz\Models\ResultModel;

class QuizResultDBRepository extends BaseDBRepository implements QuizResultRepositoryInterface
{
    public function saveResult(ResultModel $result): ResultModel
    {
        $this->insertRow([
            'user_id' => $result->userId,
            'quiz_id' => $result->quizId,
            'correct_answers' => $result->correctAnswers,
            'total_answers' => $result->totalAnswers
        ]);
        return $result;
    }

    public function getResult(int $userId, int $quizId): ResultModel
    {
        $ret = $this->getByConditions([
            'user_id' => $userId,
            'quiz_id' => $quizId
        ],
            ["user_id", "quiz_id", "correct_answers", "total_answers"]);

        if ($ret == []) {
            return new ResultModel;
        }

        return array_shift($ret);
    }

    public function getModelName(): string
    {
        return ResultModel::class;
    }

    public function getTableName(): string
    {
        return "quiz_results";
    }

    public function getPrimaryKey(): string
    {
        return "id";
    }
}