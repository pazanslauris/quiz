<?php

namespace Quiz\Repositories\Database;


use Quiz\Interfaces\AnswerRepositoryInterface;
use Quiz\Models\AnswerModel;

class AnswerDBRepository extends BaseDBRepository implements AnswerRepositoryInterface
{

    public function addAnswer(AnswerModel $answer)
    {
        $id = $this->insertRow([
            'answer' => $answer->answer,
            'question_id' => $answer->questionId,
            'is_correct' => $answer->isCorrect
        ]);
        $answer->id = $id;
        return $answer;
    }

    public function getAnswers(int $questionId): array
    {
        return $this->getByConditions(['question_id' => $questionId],
            ['id', 'answer', 'question_id', 'is_correct']);
    }

    public function getAnswer(int $answerId): AnswerModel
    {
        return $this->getById($answerId, ['id', 'answer', 'question_id', 'is_correct']);
    }

    public function getModelName(): string
    {
        return AnswerModel::class;
    }

    public function getTableName(): string
    {
        return "quiz_answers";
    }

    public function getPrimaryKey(): string
    {
        return 'id';
    }

    public function updateAnswer(AnswerModel $answer): AnswerModel
    {
        $success = $this->updateColumn([ 'id' => $answer->id,
            'answer' => $answer->answer,
            'is_correct' => $answer->isCorrect]);
        if ($success) {
            return $answer;
        }
        return new AnswerModel;
    }


    public function deleteAnswer(AnswerModel $answer): bool
    {
        return $this->delete([ 'id' => $answer->id, 'question_id' => $answer->questionId]);
    }
}