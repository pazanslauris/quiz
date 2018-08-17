<?php

namespace Quiz\Repositories;


use Quiz\Interfaces\AnswerRepositoryInterface;
use Quiz\Models\AnswerModel;

class AnswerRepository implements AnswerRepositoryInterface
{
    /** @var AnswerModel[] */
    private $answers = [];

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