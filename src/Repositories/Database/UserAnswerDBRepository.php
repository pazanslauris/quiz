<?php

namespace Quiz\Repositories\Database;


use Quiz\Interfaces\UserAnswerRepositoryInterface;
use Quiz\Models\UserAnswerModel;

class UserAnswerDBRepository extends BaseDBRepository implements UserAnswerRepositoryInterface
{

    public function saveAnswer(UserAnswerModel $userAnswer): UserAnswerModel
    {
        $id = $this->insertRow([
            'user_id' => $userAnswer->userId,
            'quiz_id' => $userAnswer->quizId,
            'question_id' => $userAnswer->questionId,
            'answer_id' => $userAnswer->answerId
        ]);
        $userAnswer->id = $id;
        return $userAnswer;
    }

    public function getAnswers(int $userId, int $quizId): array
    {
        $ret = $this->getByConditions(['user_id' => $userId, 'quiz_id' => $quizId],
            ['user_id', 'quiz_id', 'question_id', 'answer_id']);

        return $ret;
    }

    public function getAnswerToQuestion(int $userId, int $quizId, int $questionId): UserAnswerModel
    {
        $ret = $this->getByConditions([
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'question_id' => $questionId
        ],
            ['user_id', 'quiz_id', 'question_id', 'answer_id']);

        return array_shift($ret);
    }

    public function getModelName(): string
    {
        return UserAnswerModel::class;
    }

    public function getTableName(): string
    {
        return "user_answers";
    }

    public function getPrimaryKey(): string
    {
        return 'id';
    }
}